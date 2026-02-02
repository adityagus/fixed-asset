<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SusutAssetJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $tries = 3;
  public $timeout = 300; // 5 menit

  protected $bulan;
  protected $tahun;

  public function __construct($bulan = null, $tahun = null)
  {
    $this->bulan = $bulan ?? date('m');
    $this->tahun = $tahun ?? date('Y');
  }

  public function handle(): void
  {
    Log::info("Mulai proses penyusutan asset untuk bulan {$this->bulan}/{$this->tahun}");

    try {
      DB::beginTransaction();

      // Ambil semua asset yang masih aktif dengan relasi susut
      $assets = DB::table('assets')
        ->join('tb_susut', 'assets.id', '=', 'tb_susut.asset_id')
        ->where('assets.status', 'active')
        ->whereNotNull('tb_susut.tgl_reg')
        ->whereNotNull('tb_susut.nom_susut')
        ->select('assets.*', 'tb_susut.*', 'assets.id as asset_id')
        ->get();

      $processed = 0;

      foreach ($assets as $asset) {
        $this->prosesAsset($asset);
        $processed++;
      }

      DB::commit();
      Log::info("Selesai proses penyusutan {$processed} asset");

    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error penyusutan asset: " . $e->getMessage());
      throw $e;
    }
  }

  private function prosesAsset($asset)
  {
    // Ambil nilai penyusutan per bulan dari tb_susut
    $penyusutanPerBulan = $asset->nom_susut ?? 0;

    // Ambil tanggal registrasi asset
    $tglRegistrasi = $asset->tgl_reg;

    if (!$tglRegistrasi || $penyusutanPerBulan <= 0) {
      Log::warning("Asset ID {$asset->asset_id} tidak memiliki tanggal registrasi atau nilai penyusutan");
      return;
    }

    // Hitung jumlah bulan dari tanggal registrasi sampai sekarang
    $tanggalRegistrasi = new \DateTime($tglRegistrasi);
    $tanggalSekarang = new \DateTime(date('Y-m-d'));
    $interval = $tanggalRegistrasi->diff($tanggalSekarang);
    $bulanBerjalan = ($interval->y * 12) + $interval->m;

    // Pastikan tidak melebihi total umur ekonomis
    $totalUmur = $asset->total_umur ?? 0;
    if ($bulanBerjalan > $totalUmur) {
      $bulanBerjalan = $totalUmur;
    }

    // Hitung total akumulasi penyusutan
    // Total Akm Penyusutan = Penyusutan per bulan × Jumlah bulan yang sudah berjalan
    $totalAkumulasiPenyusutan = $penyusutanPerBulan * $bulanBerjalan;

    // Hitung nilai perolehan (nilai awal)
    $nilaiPerolehan = $totalUmur * $penyusutanPerBulan;

    // Hitung nilai buku
    // Nilai Buku = Nilai Perolehan - Total Akumulasi Penyusutan
    $nilaiBuku = $nilaiPerolehan - $totalAkumulasiPenyusutan;

    // Pastikan nilai buku tidak negatif
    if ($nilaiBuku < 0) {
      $nilaiBuku = 0;
    }

    // Hitung sisa umur
    $sisaUmur = $totalUmur - $bulanBerjalan;
    if ($sisaUmur < 0) {
      $sisaUmur = 0;
    }

    // Update data di tb_susut
    DB::table('tb_susut')
      ->where('asset_id', $asset->asset_id)
      ->update([
        'sisa_umur' => $sisaUmur,
        'total_akm_penyusutan' => $totalAkumulasiPenyusutan,
        'nilai_buku' => $nilaiBuku,
      ]);

    Log::info("Asset ID {$asset->asset_id}: Bulan berjalan={$bulanBerjalan}, Total Akm Penyusutan={$totalAkumulasiPenyusutan}, Nilai Buku={$nilaiBuku}");
  }
}
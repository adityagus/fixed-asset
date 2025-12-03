<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function assetReport(Request $request)
    {
        // Ambil filter dari request
        $filters = $request->all();

        // Query asset beserta relasi item
        $assets = Assets::with([
            'item.category:id,nama_katbrg,umur',
            'item.type:id,nama_tipebrg',
            'item.brand:id,nama_merkbrg',
            'registrationAsset:id,ra_number,ra_date,status',
            'susut:id,asset_id,nom_susut,total_umur,sisa_umur'
        ])
        ->select('id', 'registration_asset_number', 'asset_number', 'location', 'item_id', 'assigned_to')
        ->whereHas('registrationAsset', function ($q) {
            $q->where('status', "Full Approved");
        });

        // merge tanggal akhir
        // tanggal akhir usia 
      // item pengurangan total_umur - nom_susut
      // Hitung tanggal akhir usia untuk setiap asset berdasarkan sisa_umur dan ra_date
      $assetsCollection = $assets->get()->map(function ($asset) {
        if ($asset->susut?->sisa_umur && $asset->registrationAsset?->ra_date) {
          $remainingMonths = $asset->susut->sisa_umur;
          $asset->tgl_akhir = date('Y-m-d', strtotime("+$remainingMonths months", strtotime($asset->registrationAsset->ra_date)));
        } else {
          $asset->tgl_akhir = null;
        }
        return $asset;
      });
      
          
          

          
        if (isset($filters['status'])) {
            $assets->where('status', $filters['status']);
        }

        if (isset($filters['location'])) {
            $assets->where('location', $filters['location']);
        }

        $result = $assets->get();

        return response()->json([
            'success' => true,
            'data' => $assetsCollection,
        ]);
    }

    public function assetDepreciationReport(Request $request)
    {
        // Ambil semua aset beserta relasi item
        $assets = \App\Models\Assets::with('item')->get();

        // Siapkan data untuk tabel
        $data = [];
        $no = 1;
        foreach ($assets as $asset) {
            // Contoh perhitungan (silakan sesuaikan dengan field dan logika Anda)
            $kategori = $asset->item->category->name ?? '-';
            $kodeAset = $asset->asset_number ?? '-';
            $namaAset = $asset->item->name ?? '-';
            $lokasi = $asset->location ?? '-';
            $tglPerolehan = $asset->purchase_date ? date('d-m-Y', strtotime($asset->purchase_date)) : '-';
            $usiaEkonomis = $asset->item->economic_life_months ?? 0;
            $nilaiPerolehan = $asset->purchase_price ?? 0;
            $akmPenyPerBulan = $usiaEkonomis > 0 ? ($nilaiPerolehan / $usiaEkonomis) : 0;
            $tglSekarang = date('Y-m-d');
            $bulanBerjalan = 0;
            if ($asset->purchase_date) {
                $dt1 = new \DateTime($asset->purchase_date);
                $dt2 = new \DateTime($tglSekarang);
                $interval = $dt1->diff($dt2);
                $bulanBerjalan = ($interval->y * 12) + $interval->m;
                if ($bulanBerjalan > $usiaEkonomis) $bulanBerjalan = $usiaEkonomis;
            }
            $totalAkmPenyusutan = $akmPenyPerBulan * $bulanBerjalan;
            $nilaiBuku = $nilaiPerolehan - $totalAkmPenyusutan;
            $tanggalAkhirUsia = $asset->purchase_date ? date('d-m-Y', strtotime("+$usiaEkonomis months", strtotime($asset->purchase_date))) : '-';
            $keterangan = $asset->notes ?? '-';

            $data[] = [
                'no' => $no++,
                'no_transaksi' => $asset->registration_asset_number ?? '-',
                'kategori' => $kategori,
                'kode_aset' => $kodeAset,
                'nama_aset' => $namaAset,
                'lokasi' => $lokasi,
                'tgl_perolehan' => $tglPerolehan,
                'usia_ekonomis' => $usiaEkonomis,
                'nilai_perolehan' => number_format($nilaiPerolehan, 2, ',', '.'),
                'akm_peny_per_bulan' => number_format($akmPenyPerBulan, 2, ',', '.'),
                'total_akm_penyusutan' => number_format($totalAkmPenyusutan, 2, ',', '.'),
                'nilai_buku' => number_format($nilaiBuku, 2, ',', '.'),
                'tanggal_akhir_usia' => $tanggalAkhirUsia,
                'keterangan' => $keterangan,
            ];
        }

        // Jika ingin export Excel, bisa gunakan package Laravel Excel di sini

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function susut()
    {
        $assets = Assets::select('registration_asset_number', 'item.category.nama_katbrg', 'asset_number', 'item.nama_brg', 'location', 'ra_date', 'item.type.usia', 'item.susut.nom_susut')->with('item.category', 'item.type', 'item.brand', 'susut')->get(); // Sesuaikan relasi jika perlu
        return view('report.susut', compact('assets'));
    }
    
    public function reportBarcode ():JsonResponse {
        try{
            $assets = Assets::with('item.category', 'item.brand', 'item.type')->get();
            
            return response()->json([
                'success' => true,
                'data' => $assets,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating barcode report: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function susutReport()
    {
        try {
            // pastikan koneksi DB & query berjalan baik
            $assets = Assets::with('susut', 'registrationAsset')->get();

            foreach ($assets as $asset) {
                // cek asset relasi & isi data yg dibutuhkan
                if (!$asset->susut || !$asset->registrationAsset || !$asset->registrationAsset->ra_date) {
                    continue; // skip asset ini jika relasi tidak lengkap
                }
                // cek field tanggal susut ada
                if (empty($asset->susut->tgl_reg) || empty($asset->susut->total_umur)) {
                    continue;
                }

                try {
                    $tglAkhir = date('Y-m-d', strtotime("+{$asset->susut->total_umur} months", strtotime($asset->susut->tgl_reg)));
                    $tglSekarang = date('Y-m-d');
                    $dt1 = new \DateTime($tglSekarang);
                    $dt2 = new \DateTime($tglAkhir);
                    $interval = $dt1->diff($dt2);
                    $sisaBulan = ($interval->y * 12) + $interval->m;
                    if ($sisaBulan < 0)
                        $sisaBulan = 0;

                    $asset->susut->sisa_umur = $sisaBulan;
                    $asset->susut->save();
                } catch (\Exception $e) {
                    // Jika gagal hitung/datetime
                    // Bisa log error atau lanjut asset berikutnya
                    \Log::error('Gagal update sisa_umur asset ID: ' . $asset->id . ' error: ' . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $assets,
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error koneksi atau query
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // General error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}

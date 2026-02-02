<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SusutAssetJob;

class SusutAssetCommand extends Command
{
  protected $signature = 'asset:susut {bulan?} {tahun?}';
  protected $description = 'Proses penyusutan asset';

  public function handle()
  {
    $bulan = $this->argument('bulan');
    $tahun = $this->argument('tahun');

    $this->info('Memproses penyusutan asset...');

    // Jalankan job secara sync (langsung)
    try {
      $job = new SusutAssetJob($bulan, $tahun);
      $job->handle();
      $this->info('Proses penyusutan berhasil diselesaikan!');
    } catch (\Exception $e) {
      $this->error('Error: ' . $e->getMessage());
      return 1;
    }

    return 0;
  }
}
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
        $assetNew = Assets::with('item.category:id,nama_katbrg,umur',
            'item.type:id,nama_tipebrg',
            'item.brand:id,nama_merkbrg', 'registrationAsset', 'susut')->get();

        // Query asset beserta relasi item
        $assets = Assets::with([
            'item.category:id,nama_katbrg,umur',
            'item.type:id,nama_tipebrg',
            'item.brand:id,nama_merkbrg',
            // 'registrationAsset:id,ra_number,ra_date,status',
            'susut:id,asset_id,nom_susut,total_umur,sisa_umur'
        ])
        ->select('id', 'registration_asset_number', 'asset_number', 'ra_date', 'location', 'item_id', 'assigned_to', 'is_asset');
        // ->select('id', 'registration_asset_number', 'asset_number', 'location', 'item_id', 'assigned_to');
        // ->whereHas('registrationAsset', function ($q) {
        //     $q->where('status', "Full Approved");
        // });
        
        // dd('testtt');

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
    /**
     * Get paginated asset report with limit and offset.
     */
    public function assetReportPaginated(Request $request): JsonResponse
    { 
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);

        $filters = $request->all();

        $assetsQuery = Assets::with([
            'item.category:id,nama_katbrg,umur',
            'item.type:id,nama_tipebrg',
            'item.brand:id,nama_merkbrg',
            'susut:id,asset_id,nom_susut,total_umur,sisa_umur'
        ])
        ->select('id', 'registration_asset_number', 'asset_number', 'ra_date', 'location', 'item_id', 'assigned_to', 'is_asset');

        if (isset($filters['status'])) {
            $assetsQuery->where('status', $filters['status']);
        }

        if (isset($filters['location'])) {
            $assetsQuery->where('location', $filters['location']);
        }

        $total = $assetsQuery->count();

        $assets = $assetsQuery
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($asset) {
                if ($asset->susut?->sisa_umur && $asset->registrationAsset?->ra_date) {
                    $remainingMonths = $asset->susut->sisa_umur;
                    $asset->tgl_akhir = date('Y-m-d', strtotime("+$remainingMonths months", strtotime($asset->registrationAsset->ra_date)));
                } else {
                    $asset->tgl_akhir = null;
                }
                return $asset;
            });

        return response()->json([
            'success' => true,
            'data' => $assets,
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }
    
    public function getCabangAssetReportPaginated(Request $request)
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $cabangId = $request->input('cabang_id');
        $kapitalis = $request->input('tipe_aset');

        $query = Assets::with('item.category', 'item.brand', 'item.type');

        // Jika dua-duanya kosong, ambil semua data
        if (empty($cabangId) && empty($kapitalis)) {
            $assets = $query->get();
        }
        // Jika hanya cabang yang diisi
        elseif (!empty($cabangId) && empty($kapitalis)) {
            $assets = $query->where('location', $cabangId)->get();
        }
        // Jika hanya kapitalis yang diisi
        elseif (empty($cabangId) && !empty($kapitalis)) {
            $assets = $query->where('is_asset', $kapitalis)->get();
        }
        // Jika dua-duanya diisi
        else {
            $assets = $query->where('location', $cabangId)
                           ->where('is_asset', $kapitalis)
                           ->get();
        }
        
        $assets = $assets->skip($offset)->take($limit);

        return response()->json([
            'success' => true,
            'data' => $assets,
            'total' => $assets->count(),
            'limit' => $limit,
            'offset' => $offset,
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
    
    public function headerAssetStatistik()
    {
        $totalAssets = Assets::count();
        $activeAssets = Assets::where('status', 'Active')->count();
        $inActiveAssets = Assets::where('status', 'Inactive')->count();
        $depreciatedAssets = Assets::where('status', 'Depreciated')->count();
        $totalDepreciated = Assets::where('status', 'Depreciated')->sum('purchase_price');
        
        $totalValue = Assets::sum('purchase_price');

        return response()->json([
            'success' => true,
            'data' => [
                'active_assets' => $activeAssets,
                'inactive_assets' => $inActiveAssets,
                'depreciated_assets' => $depreciatedAssets,
                'total_assets' => $totalAssets,
                'total_value' => $totalValue,
                'depreciation_value' => $totalDepreciated,
            ],
        ]);
    }
    
    public function bodyAssetStatistik()
    {
        // Definisikan tipe aset yang diinginkan
        // Definisikan tipe aset sebagai object
        $asset_type = (object)[
            'kendaraan' => 0,
            'mesin_kantor' => 0,
            'peralatan_kayu' => 0,
            'other' => 0,
        ];

        // Mapping kategori ke tipe
        $type_map = [
            'kendaraan' => [1, 2],
            'mesin_kantor' => [3, 4],
            'peralatan_kayu' => [5],
        ];

        $assets = Assets::with('item.category')->get();

        foreach ($assets as $asset) {
            $categoryId = $asset->item->category->id ?? null;
            if (in_array($categoryId, $type_map['kendaraan'], true)) {
            $asset_type->kendaraan++;
            } elseif (in_array($categoryId, $type_map['mesin_kantor'], true)) {
            $asset_type->mesin_kantor++;
            } elseif (in_array($categoryId, $type_map['peralatan_kayu'], true)) {
            $asset_type->peralatan_kayu++;
            } else {
            $asset_type->other++;
            }
        }

        $assetsByType = $asset_type;
            
            // Menghitung asset berdasarkan usia: 0-5 tahun, 6-10 tahun, >10 tahun
            $assets = Assets::with('item.category')->get();

            $assets_0_5 = $assets->filter(function ($asset) {
                if (!$asset->ra_date) {
                    return false;
                }
                $acquisitionDate = new \DateTime($asset->ra_date);
                $now = new \DateTime();
                $interval = $acquisitionDate->diff($now);
                $years = $interval->y;
                return $years >= 0 && $years <= 5;
            });

            $assets_6_10 = $assets->filter(function ($asset) {
                if (!$asset->ra_date) {
                    return false;
                }
                $acquisitionDate = new \DateTime($asset->ra_date);
                $now = new \DateTime();
                $interval = $acquisitionDate->diff($now);
                $years = $interval->y;
                return $years >= 6 && $years <= 10;
            });

            $assets_10_plus = $assets->filter(function ($asset) {
                if (!$asset->ra_date) {
                    return false;
                }
                $acquisitionDate = new \DateTime($asset->ra_date);
                $now = new \DateTime();
                $interval = $acquisitionDate->diff($now);
                $years = $interval->y;
                return $years > 10;
            });

            // Contoh: jika ingin menampilkan jumlah masing-masing kelompok
            $assetsByAge = array(
                'age_0_5' => $assets_0_5->count(),
                'age_6_10' => $assets_6_10->count(),
                'age_above_10' => $assets_10_plus->count(),
            );
            // dd($assetsByAge);
            // $assets_0_5->count(), $assets_6_10->count(), $assets_10_plus->count()
            // menghitung diatas 6 tahun, masih operasional , perlu diganti
            $asset_up_6 = $assets->filter(function ($asset) {
                if (!$asset->ra_date) {
                    return false;
                }
                $acquisitionDate = new \DateTime($asset->ra_date);
                $now = new \DateTime();
                $interval = $acquisitionDate->diff($now);
                $years = $interval->y;
                return $years >= 6; // && $asset->status == 'Active'
            });
            // dd($assets);
            $still_operational = $assets->filter(function ($asset) {
                return $asset->status == 'active' || $asset->status == 'depreciated';
            });
            $non_operational = $assets->filter(function ($asset) {
                return $asset->status == 'inactive';
            });
            $change_needed = $assets->filter(function ($asset) use ($asset_up_6, $still_operational) {
                return $asset_up_6->contains($asset) && $still_operational->contains($asset);
            });
            
            $totalAssetsCount = $assets->count();
            // Hitung persentase, hindari pembagian nol
            $percent = function($count) use ($totalAssetsCount) {
                return $totalAssetsCount > 0 ? round(($count / $totalAssetsCount) * 100, 1) : 0;
            };

            $assetsByStatus = [
                'still_operational' => [
                    'count' => $still_operational->count(),
                    'percent' => $percent($still_operational->count())
                ],
                'non_operational' => [
                    'count' => $non_operational->count(),
                    'percent' => $percent($non_operational->count())
                ],
                'change_needed' => [
                    'count' => $change_needed->count(),
                    'percent' => $percent($change_needed->count())
                ],
            ];

        return response()->json([
            'success' => true,
            'data' => [
                'asset_types' => $assetsByType,
                'asset_status' => $assetsByStatus,
                'asset_age' => $assetsByAge,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MasterBrand;
use App\Models\MasterCategory;
use App\Models\MasterItem;
use App\Models\MasterType;
use App\Models\MasterVendor;
use App\Models\MasterCabang;
use App\Models\MasterDepartment;
use App\Models\MasterJabatan;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    // CREATE Master Barang
    public function createMasterBarang(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_brg' => 'required|string|max:255',
                'id_katbrg' => 'required|exists:mst_katbrg,id',
                'id_tipebrg' => 'required|exists:mst_tipebrg,id',
                'id_merkbrg' => 'required|exists:mst_merkbrg,id',
                'ket_brg' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);
            $barang = MasterItem::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan',
                'data' => $barang
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // UPDATE Master Barang
    public function updateMasterBarang(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nama_brg' => 'required|string|max:255',
                'id_katbrg' => 'required|exists:mst_katbrg,id',
                'id_tipebrg' => 'required|exists:mst_tipebrg,id',
                'id_merkbrg' => 'required|exists:mst_merkbrg,id',
                'ket_brg' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);
            $barang = MasterItem::findOrFail($id);
            $barang->update($validated);
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diupdate',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE Master Barang
    public function deleteMasterBarang($id)
    {
        try {
            $barang = MasterItem::findOrFail($id);
            $barang->update(['status' => 'inactive']);
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    //assets master barang
    public function getMasterBarang()
    {
        $res = MasterItem::with('category', 'type', 'brand')
            ->where('status', 'active')
            ->get();
        return response()->json($res);
    }
    
    public function getKategoriList()
    {
        try {
            // Gunakan MasterItem untuk get kategori yang unique
            $res = MasterCategory::where('status', 1)->get();
            
            return response()->json([
                'success' => true,
                'data' => $res
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function createKategori(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_katbrg' => 'required|string|max:255',
                'umur' => 'required|integer',
                'status' => 'required|boolean',
                'coa1' => 'nullable|integer',
                'coa2' => 'nullable|integer',
                'coa3' => 'nullable|integer',
                'coa4' => 'nullable|integer',
            ]);
            
            $kategori = MasterCategory::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $kategori
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateKategori(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nama_katbrg' => 'required|string|max:255',
                'umur' => 'required|integer',
                'status' => 'required|boolean',
                'coa1' => 'nullable|integer',
                'coa2' => 'nullable|integer',
                'coa3' => 'nullable|integer',
                'coa4' => 'nullable|integer',
            ]);
            
            $kategori = MasterCategory::findOrFail($id);
            $kategori->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diupdate',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteKategori($id)
    {
        try {
            $kategori = MasterCategory::findOrFail($id);
            $kategori->update(['status' => 0]);
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getTipeBarangList()
    {
        try {
            $res = MasterType::where('status', 1)->get();
            return response()->json([
                'success' => true,
                'data' => $res
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function createTipeBarang(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_tipebrg' => 'required|string|max:255',
                'kode' => 'nullable|string|max:50',
                'status' => 'nullable|boolean',
            ]);
            
            $tipe = MasterType::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Tipe barang berhasil ditambahkan',
                'data' => $tipe
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateTipeBarang(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nama_tipebrg' => 'required|string|max:255',
                'kode' => 'nullable|string|max:50',
                'status' => 'nullable|boolean',
            ]);
            
            $tipe = MasterType::findOrFail($id);
            $tipe->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Tipe barang berhasil diupdate',
                'data' => $tipe
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteTipeBarang($id)
    {
        try {
            $tipe = MasterType::findOrFail($id);
            $tipe->update(['status' => 0]);
            
            return response()->json([
                'success' => true,
                'message' => 'Tipe barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getMerkList()
    {
        try {
            $res = MasterBrand::where('status', 1)->get();
            
            return response()->json([
                'success' => true,
                'data' => $res
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function createMerk(Request $request)
    {
        try {
            $request->validate([
                'nama_merkbrg' => 'required|string|max:255'
            ]);
            
            $merk = MasterBrand::create([
                'nama_merkbrg' => $request->nama_merkbrg
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Merek berhasil ditambahkan',
                'data' => $merk
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateMerk(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_merkbrg' => 'required|string|max:255'
            ]);
            
            $merk = MasterBrand::findOrFail($id);
            $merk->update([
                'nama_merkbrg' => $request->nama_merkbrg
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Merek berhasil diupdate',
                'data' => $merk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteMerk($id)
    {
        try {
            $merk = MasterBrand::findOrFail($id);
            $merk->update(['status' => 0]);
            
            return response()->json([
                'success' => true,
                'message' => 'Merek berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getMasterKategori()
    {
        $res = MasterItem::select('id_kategori')->distinct()->get();
        return response()->json($res);
    }
    
    public function tipeBarang(){
        $res = MasterType::all();
        return response()->json($res);
    }
    
    public function getVendorList()
    {
        $res = MasterVendor::where('status', 1)->get();
        return response()->json($res);
    }
    
    public function createVendor(Request $request)
{
    try {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'telp1' => 'nullable|string|max:20',
            'telp2' => 'nullable|string|max:20',
            'pic' => 'nullable|string|max:100',
            'nm_bank' => 'nullable|string|max:100',
            'no_rek' => 'nullable|string|max:50',
            'atas_nm' => 'nullable|string|max:100',
            'status' => 'required|boolean',
        ]);
        
        $vendor = MasterVendor::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Vendor berhasil ditambahkan',
            'data' => $vendor
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function updateVendor(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'telp1' => 'nullable|string|max:20',
            'telp2' => 'nullable|string|max:20',
            'pic' => 'nullable|string|max:100',
            'nm_bank' => 'nullable|string|max:100',
            'no_rek' => 'nullable|string|max:50',
            'atas_nm' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);
        
        $vendor = MasterVendor::findOrFail($id);
        $vendor->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Vendor berhasil diupdate',
            'data' => $vendor
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function deleteVendor($id)
{
    try {
        $vendor = MasterVendor::findOrFail($id);
        $vendor->update(['status' => 0]);
        
        return response()->json([
            'success' => true,
            'message' => 'Vendor berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    
    public function masterCabangChildren()
    {
        try {
            $query = \DB::connection('db2')
                ->table('tblcabang as cb')
                ->leftJoin('tblarea as area', 'cb.fk_area', '=', 'area.kd_area')
                ->select('cb.kd_cabang', 'cb.nm_cabang', 'cb.cabang_active', 'area.kd_area', 'area.area_active')
                ->orderBy('area.kd_area', 'asc')
                ->orderBy('cb.kd_cabang', 'asc')
                ->where([
                    'cabang_active' => 'true',
                    'area_active' => 'true'
                ])
                ->get();

            $children = [];
            foreach ($query as $item) {
                $children[] = [
                    "id_area" => $item->kd_cabang,
                    "nm_area" => $item->nm_cabang,
                    "kd_area" => $item->kd_area
                ];
            }

            return response()->json($children);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
    public function masterAreaParents()
    {
        try {
            $query = \DB::connection('db2')
                ->table('tblarea')
                ->select('kd_area', 'nm_area')
                ->where('area_active', 'true')
                ->orderBy('kd_area', 'asc')
                ->get();

            $parents = [];
            foreach ($query as $item) {
                $parents[] = [
                    "id_area" => $item->kd_area,
                    "nm_area" => $item->nm_area
                ];
            }

            return response()->json($parents);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
}

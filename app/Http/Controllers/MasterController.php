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
    //assets master barang
    public function getMasterBarang()
    {
        $res = MasterItem::with('category', 'type', 'brand')->get();
        return response()->json($res);
    }
    
    public function getKategoriList()
    {
        try {
            // Gunakan MasterItem untuk get kategori yang unique
            $res = MasterCategory::all();
            
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
    
    public function getTipeBarangList()
    {
        try {
            $res = MasterType::all();
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
    
    public function getMerkList()
    {
        try {
            $res = MasterBrand::all();
            
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
            $merk->delete();
            
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
        $res = MasterVendor::all();
        return response()->json($res);
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

<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
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
        $res = MasterItem::with('category')->get();
        return response()->json($res);
    }
    
    
    public function getVendorList()
    {
        $res = MasterVendor::all();
        return response()->json($res);
    }
    
    
    public function getMasterKategori()
    {
        $res = MasterItem::select('id_kategori')->distinct()->get();
        return response()->json($res);
    }
    
}

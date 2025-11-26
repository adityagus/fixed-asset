<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportMasterController extends Controller
{
    public function showForm()
    {
        return view('import-master');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');
        $sheets = Excel::toArray([], $file);

        // Sheet order: mst_katbrg, mst_tipebrg, mst_merkbrg, mst_vendor, mst_brg
        $sheetNames = ['mst_katbrg', 'mst_tipebrg', 'mst_merkbrg', 'mst_vendor', 'mst_brg'];
        foreach ($sheetNames as $idx => $table) {
            if (isset($sheets[$idx])) {
                $rows = $sheets[$idx];
                $header = array_map('strtolower', $rows[0]);
                foreach (array_slice($rows, 1) as $row) {
                    $data = array_combine($header, $row);
                    DB::table($table)->updateOrInsert(
                        ['id' => $data['id'] ?? null], // pastikan ada kolom id, jika tidak sesuaikan
                        $data
                    );
                }
            }
        }

        return back()->with('success', 'Import success!');
    }
}

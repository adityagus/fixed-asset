<?php

namespace App\Models;

use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Login extends Model
{
    use HasFactory;
    public static function cekLogin($user)
    {
      // Asumsi koneksi db2 sudah di-setup di config/database.php
      try{
        // $query = \DB::connection('db2')
        // ->table('tbluser as a')
        // ->select('a.username', 'a.password', 'b.nm_depan', 'b.nm_belakang', 'c.kd_jabatan', 'nm_jabatan', 'a.fk_cabang_user')
        // ->join('tblkaryawan as b', 'a.fk_karyawan', '=', 'b.npk')
        // ->join('tbljabatan as c', 'b.fk_jabatan', '=', 'c.kd_jabatan')
        // ->where('a.active', 't')
        // ->where('a.username', 'like', "%" . $user . "%")
        // ->get();
      $query = [
        (object)[
          'username' => 'aditya.gustian',
          'password' => 'Gadai@Adit123', // contoh password terenkripsi
          'nama' => 'Nama',
          'nm_depan' => 'Nama',
          'nm_belakang' => 'Belakang',
          'kd_jabatan' => 'JBT-032',
          'fk_cabang_karyawan' => 'CAB-001'
        // Tambahkan data dummy lainnya jika perlu
        ]
      ];
        return $query;
      
      } catch (QueryException $e) {
        return response()->json([
          'success' => false,
          'message' => 'Internal Server Error' . $e->getMessage()
        ], 500);
      }
      

    }
}

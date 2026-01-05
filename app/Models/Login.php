<?php

namespace App\Models;

use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PDOException;

class Login extends Model
{
    use HasFactory;
    public static function cekLogin($user)
    {
      // Asumsi koneksi db2 sudah di-setup di config/database.php
      try{
        //  KACAB, KAAREA, IT SPV, IT HEAD, GA STAFF, GA HEAD
        // Set PDO timeout to 3 seconds
        config(['database.connections.db2.options' => [
            \PDO::ATTR_TIMEOUT => 3,
        ]]);
        // cek connection 
        $connection = \DB::connection('db2')->getPdo();
        // $group = ['JBT-021', 'JBT-005', 'JBT-032', 'JBT-006', 'JBT-018', 'JBT-017'];
        $query = \DB::connection('db2')
        ->table('tbluser as a')
        ->select('a.username', 'a.password', 'b.nm_depan', 'b.nm_belakang', 'c.kd_jabatan', 'c.nm_jabatan', 'a.fk_cabang_user', 'd.kd_cabang')
        ->join('tblkaryawan as b', 'a.fk_karyawan', '=', 'b.npk')
        ->join('tbljabatan as c', 'b.fk_jabatan', '=', 'c.kd_jabatan')
        ->leftJoin('tblcabang as d', 'a.fk_cabang_user', '=', 'd.kd_cabang')
        ->where('a.active', 't')
        ->where('a.username',"{$user}")
        // ->whereIn('c.kd_jabatan', $group)
        ->get();
      // $query = [
      //   (object)[
      //     'username' => 'aditya.gustian',
      //     'password' => 'Gadai@Adit123', // contoh password terenkripsi
      //     'nama' => 'Aditya Gustian',
      //     'nm_depan' => 'Aditya',
      //     'nm_belakang' => 'Gustian',
      //     'kd_jabatan' => 'JBT-032',
      //     'fk_cabang_karyawan' => 'CAB-001'
      //   // Tambahkan data dummy lainnya jika perlu
      //   ]
      // ];
        return $query;
      } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Database connection failed: ',
                'detail' => $e->getMessage(),
                'type' => 'connection_error'
            ];
        
      } catch (QueryException $e) {
        return response()->json([
          'success' => false,
          'message' => 'Internal Server Error: ' . $e->getMessage()
        ], 500);
      }
      

    }
    
    
    public static function approvalCM(request $request)
    {
        try {
            $result = \DB::connection('db2')
                ->table('tbluser as t')
                ->select(
                    't.username',
                    't.password',
                    't.active',
                    't2.nm_depan',
                    't2.nm_belakang',
                    't3.kd_jabatan',
                    't3.nm_jabatan',
                    't.fk_cabang_user',
                    't4.fk_area'
                )
                ->leftJoin('tblkaryawan as t2', 't.fk_karyawan', '=', 't2.npk')
                ->leftJoin('tbljabatan as t3', 't2.fk_jabatan', '=', 't3.kd_jabatan')
                ->leftJoin('tblcabang as t4', 't.fk_cabang_user', '=', 't4.kd_cabang')
                ->where('t3.nm_jabatan', 'Ka Area')
                ->where('t4.fk_area', '202')
                ->where('t.active', true)
                ->limit(1)
                ->get();

            return $result;
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}

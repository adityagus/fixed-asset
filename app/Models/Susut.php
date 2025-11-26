<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Susut extends Model
{
    use HasFactory;
    
    public $table = 'tb_susut';
    protected $fillable = [
        'asset_id',
        'cbg_id',
        'nom_susut',
        'sisa_umur',
        'sts_tmt',
        'sts_jual',
        'tgl_reg',
        'total_umur',
    ];
    
    public $timestamps = false;
}

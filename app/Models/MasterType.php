<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterType extends Model
{
    use HasFactory;
    
    public $table = 'mst_tipebrg';
    
    protected $fillable = [
        'nama_tipebrg',
        'kode',
        'status',
    ];
}

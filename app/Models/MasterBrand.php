<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBrand extends Model
{
    use HasFactory;
    
    public $table = 'mst_merkbrg';
    
    protected $fillable = [
        'nama_merkbrg',
        'status',
    ];
}

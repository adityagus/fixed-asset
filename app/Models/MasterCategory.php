<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
    use HasFactory;
    
    public $table = 'mst_katbrg';
    
    protected $fillable = [
        'nama_katbrg',
        'type_katbrg',
        'umur',
        'status',
        'coa1',
        'coa2',
        'coa3',
        'coa4',
    ];
    
}

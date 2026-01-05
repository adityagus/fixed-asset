<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class MasterItem extends Model
{
    protected $fillable = [
      'nama_brg',
      'id_katbrg',
      'id_tipebrg',
      'id_merkbrg',
      'ket_brg',
      'status',
    ];
    use HasFactory;
    public $table = 'mst_brg';
    
    public function category()
    {
        return $this->belongsTo(MasterCategory::class, 'id_katbrg', 'id');
    }

    public function brand()
    {
      return $this->belongsTo(MasterBrand::class, 'id_merkbrg', 'id');
    }
    public function type()
    {
      return $this->belongsTo(MasterType::class, 'id_tipebrg', 'id');
    }
}
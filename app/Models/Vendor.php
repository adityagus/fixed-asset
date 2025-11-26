<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    
    protected $table = 'mst_vendor';
    
    protected $fillable = [
        'nama',
        'alamat',
        'kota',
        'telp1',
        'telp2',
        'pic',
        'nm_bank',
        'no_rek',
        'status',
    ];
    
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
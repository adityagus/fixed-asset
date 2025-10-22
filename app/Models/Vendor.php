<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    
    protected $table = 'mst_vendor';
    
    protected $fillable = [
        'vendor_name',
        'contact_person',
        'phone',
        'email',
        'address',
    ];
    
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
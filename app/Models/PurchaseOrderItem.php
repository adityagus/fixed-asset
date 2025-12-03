<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'item_id',
        'purchase_order_number',
        'pengajuan',
        'quantity',
        'unit_price',
        'total_price',
    ];
    
    public $timestamps = true;

    public function itemMaster()
    {
      return $this->belongsTo(MasterItem::class, 'item_id', 'id');
    }
}

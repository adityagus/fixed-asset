<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use HasFactory;
    
    public $fillable = [
        'item_id',
        'purchase_request_number',
        'quantity',
        'is_locked',
        'pengajuan',
        'pengajuan',
        'unit_price',
        'total_price',
    ];
    
    // date update not update
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    
    public function PurchaseRequest() {
      return $this->belongsTo(PurchaseRequest::class, 'purchase_request_number', 'pr_number');
    }
    
    public function itemMaster()
  {
    return $this->belongsTo(MasterItem::class, 'item_id', 'id');
  }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    
    public $fillable = [
        'po_number',
        'purchase_request_id',
        'vendor_id',
        'total_amount',
        'status',
        'created_by',
        'updated_by',
    ];
    
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
    
    public function purchaseApprovals()
    {
        return $this->hasMany(PurchaseOrderApproval::class, 'purchase_order_id');
    }
    
    
}

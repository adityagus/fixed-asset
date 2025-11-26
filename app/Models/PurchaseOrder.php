<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'po_number',
        'po_date',
        'purchase_request_number',
        'vendor_id',
        'total_amount',
        'status',
        'created_by',
        'updated_by',
    ];
    
    public $timestamps = true;
    
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_number', 'po_number');
    }
    
    public function approvals()
    {
        return $this->hasMany(PurchaseOrderApproval::class, 'request_number', 'po_number');
    }
    
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_number', 'pr_number');
    }
    
    
}

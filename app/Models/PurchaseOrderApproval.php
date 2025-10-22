<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderApproval extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_order_id',
        'approved_by',
        'approval_date',
        'status',
        'remarks',
    ];
    
    
    public $timestamps = false;
    // created_at and updated_at are managed by Eloquent automatically
    
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderApproval extends Model
{
    use HasFactory;
    protected $fillable = [
      'type', 'request_number', 'layer', 'approver_by', 'approval_date', 'email', 'jabatan', 'approval_status', 'note'
    ];
    
    
    public $timestamps = true;
    
    
    // created_at and updated_at are managed by Eloquent automatically
    
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'request_number', 'po_number');
    }
}
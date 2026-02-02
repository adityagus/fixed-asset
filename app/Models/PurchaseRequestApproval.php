<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestApproval extends Model
{
    use HasFactory;
    
    protected $fillable = [
      'type', 'request_number', 'layer', 'approver_by', 'approval_date', 'email', 'jabatan', 'approval_status', 'note'
    ];

    public $timestamps = true;

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'request_number', 'pr_number');
    }
}

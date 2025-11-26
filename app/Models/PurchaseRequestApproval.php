<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestApproval extends Model
{
    use HasFactory;
    
    protected $fillable = [
      'type', 'request_number', 'layer', 'approver_by', 'approval_date', 'email', 'jabatan', 'approval_status'
    ];

    public $timestamps = true;

}

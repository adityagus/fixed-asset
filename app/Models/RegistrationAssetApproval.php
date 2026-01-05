<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationAssetApproval extends Model
{
    use HasFactory;
    protected $fillable = [
      'type', 'request_number', 'layer', 'approver_by', 'approval_date', 'email', 'jabatan', 'approval_status', 'note'
    ];
    
    public $timestamps = true;
    
    
    public function RegistrationAsset()
    {
        return $this->belongsTo(RegistrationAsset::class, 'asset_registration_number');
    } 
}

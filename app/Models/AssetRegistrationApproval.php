<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRegistrationApproval extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_registration_id',
        'approved_by',
        'approval_date',
        'status',
        'remarks',
    ];
    
    public $timestamps = false;
    
    
    public function assetRegistration()
    {
        return $this->belongsTo(AssetRegistration::class, 'asset_registration_id');
    } 
}

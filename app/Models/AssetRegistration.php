<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRegistration extends Model
{
    use HasFactory;
    protected $fillable = [
        'ar_number',
        'registration_number',
        'registration_date',
        'registered_by',
        'total_value',
        'status',
    ];

  public function assetRegistrationItems()
  {
    return $this->hasMany(AssetRegistrationItem::class, 'asset_registration_id');
  }
  
  public function assetRegistrationApprovals()
  {
    return $this->hasMany(AssetRegistrationApproval::class, 'asset_registration_id');
  }
}

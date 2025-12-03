<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationAssetItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'registration_asset_number',
        'item_id',
        'pengajuan',
        'quantity',
        'unit_price',
        'total_price',
    ];
    
    public function RegistrationAsset()
    {
        return $this->belongsTo(RegistrationAsset::class, 'ar_number');
    }
    
    public function itemMaster()
    {
      return $this->belongsTo(MasterItem::class, 'item_id', 'id');
    }
    
}

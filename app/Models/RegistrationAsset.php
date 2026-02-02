<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RegistrationAsset extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ra_number',
        'purchase_order_number',
        'created_by',
        'ra_date',
        'invoice_date',
        'status',
        'created_at',
        'updated_at',
    ];
    
    public $timestamps = true;

  public function registrationAssetItems()
  {
    return $this->hasMany(RegistrationAssetItem::class, 'registration_asset_number', 'ra_number');
  }
  
  public function approvals()
  {
    return $this->hasMany(RegistrationAssetApproval::class, 'request_number', 'ra_number');
  }
  
  public function purchaseOrder()
  {
    return $this->belongsTo(PurchaseOrder::class, 'purchase_order_number', 'po_number');
  }
  
}

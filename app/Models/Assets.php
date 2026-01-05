<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    use HasFactory;
    
    protected $fillable = [
      'asset_number',
      'regist_item_id',
      'item_id',
      'asset_type',
      'is_asset',
      'purchase_date',
      'purchase_price',
      'purchase_order_number',
      'purchase_request_number',
      'registration_asset_number',
      'location',
      'status',
      'assigned_to',
      'vendor_id',
      'warranty_until',
      'notes',
    ];
    
    
    public function purchaseRequest(){
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_number', 'request_number');
    }
    
    public function purchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_number', 'order_number');
    }
    
    public function registrationAsset(){
        return $this->belongsTo(RegistrationAsset::class, 'registration_asset_number', 'ra_number');
    }
    
    public function item()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'id');
    }
    
    
    public function susut()
    {
        return $this->hasOne(Susut::class, 'asset_id', 'id');
    }
    
}

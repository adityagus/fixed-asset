<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRegistrationItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_registration_id',
        'item_name',
        'quantity',
        'unit_price',
        'total_price',
    ];
    
}

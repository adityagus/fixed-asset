<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use HasFactory;
    
    public $fillable = [
        'item_name',
        'purchase_request_id',
        'quantity',
        'estimate_unit_price',
        'total_price',
    ];
}

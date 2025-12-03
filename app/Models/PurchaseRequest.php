<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;
    
    protected $table = 'purchase_requests';
    protected $fillable = [
        'pr_number',
        'pr_date',
        'jenis_permintaan',
        'url_file',
        'created_by',
        'department',
        'cabang',
        'status',
    ];
    
    // pr_date is automatically set when creating a new record
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->pr_date)) {
                $model->pr_date = now();
            }
        });
    }
    
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    public function purchaseRequestItems()
    {
      return $this->hasMany(PurchaseRequestItem::class, 'purchase_request_number', 'pr_number');
    }
    
    public function approvals()
    {
      return $this->hasMany(PurchaseRequestApproval::class, 'request_number', 'pr_number');
    }
    
    public function attachments()
    {
      return $this->hasMany( Attachment::class, 'purchase_request_number', 'pr_number');
    }
}

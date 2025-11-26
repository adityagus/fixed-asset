<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'form_number',
        'form_type',
        'text',
        'sender',
        'time',
        'created_by',
    ];
    
    public $timestamps = false;
}

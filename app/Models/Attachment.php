<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = [
      'attachable_type',
      'form_number',
      'size',
      'url_file',
      'file_name',
      'uploaded_by',
    ];
    
    public $timestamps = true;
  }

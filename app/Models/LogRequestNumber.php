<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRequestNumber extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_number',
        'type',
        'year',
        'nomor',
    ];
    
    public static function createRequest($type)
    {
        $year = date('Y');
        $latestRequest = self::where('type', $type)->where('year', $year)->orderBy('nomor', 'desc')->first();
        if(!$latestRequest) {
            $latestRequest = (object)['nomor' => 0];
        }
        $nomorUrut = $latestRequest->nomor + 1;
        $request_number = $type . '-' . $year . '-' . str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);
        return self::create([
          'request_number' => $request_number,
          'type' => $type,
          'year' => $year,
          'nomor' => $nomorUrut,
        ]);
    }
}

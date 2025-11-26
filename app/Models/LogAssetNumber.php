<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAssetNumber extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'asset_number',
        'nomor',
        'type',
        'year',
    ];
    
    
    public static function createAsset($type)
    {
        $year = date('Y');
        $latestRequest = self::where('type', $type)->orderBy('nomor', 'desc')->first();
        if(!$latestRequest) {
            $latestRequest = (object)['nomor' => 0];
        }
        $nomorUrut = $latestRequest->nomor + 1;
        $asset_number = $type . '-' . $year . '-' . str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);
        return self::create([
          'asset_number' => $asset_number,
          'type' => $type,
          'year' => $year,
          'nomor' => $nomorUrut,
        ]);
    }
}

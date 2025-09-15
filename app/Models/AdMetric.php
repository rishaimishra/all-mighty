<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdMetric extends Model
{
     protected $fillable = [
        'ad_id',
        'date',
        'impressions',
        'clicks',
        'conversions',
        'spend'
    ];

    protected $casts = [
        'date' => 'date',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'conversions' => 'integer',
        'spend' => 'decimal:2'
    ];

    public function ad()
    {
        return $this->belongsTo(Ads::class);
    }
}

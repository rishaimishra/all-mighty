<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignMetric extends Model
{
     protected $fillable = [
        'campaign_id',
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

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}

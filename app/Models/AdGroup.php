<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdGroup extends Model
{
     protected $fillable = [
        'campaign_id',
        'name',
        'status',
        'default_bid'
    ];

    protected $casts = [
        'default_bid' => 'decimal:2'
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}

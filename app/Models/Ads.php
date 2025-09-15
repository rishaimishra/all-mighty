<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $fillable = [
        'ad_group_id',
        'name',
        'status',
        'type',
        'headline',
        'description',
        'image_url',
        'final_url'
    ];

    // Relationships
    public function adGroup()
    {
        return $this->belongsTo(AdGroup::class);
    }

    public function metrics()
    {
        return $this->hasMany(AdMetric::class);
    }
}

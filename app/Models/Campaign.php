<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
     protected $fillable = [
        'tenant_id',
        'ad_account_id',
        'name',
        'status',
        'daily_budget',
        'start_date',
        'end_date',
        'targeting_criteria'
    ];

    protected $casts = [
        'daily_budget' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'targeting_criteria' => 'array'
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function adAccount()
    {
        return $this->belongsTo(AdAccount::class);
    }

    public function adGroups()
    {
        return $this->hasMany(AdGroup::class);
    }

    public function metrics()
    {
        return $this->hasMany(CampaignMetric::class);
    }
}

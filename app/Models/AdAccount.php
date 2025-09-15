<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAccount extends Model
{
     protected $fillable = [
        'tenant_id',
        'platform_type',
        'platform_account_id',
        'credentials_encrypted',
        'status'
    ];

    protected $casts = [
        'credentials_encrypted' => 'encrypted'
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}

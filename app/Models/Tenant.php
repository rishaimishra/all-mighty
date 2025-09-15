<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'address',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    public function adAccounts()
    {
        return $this->hasMany(AdAccount::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}

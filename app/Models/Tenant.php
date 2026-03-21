<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Tenant extends Model
{
    use HasFactory, Billable;

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public function members()
    {
        return $this->hasMany(MemberProfile::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'default_tenant_id');
    }

    public function isSubscribed(): bool
    {
        return $this->subscribed() || $this->onTrial();
    }
}

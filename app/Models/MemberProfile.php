<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use App\Services\TenantContextService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProfile extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'string',
    ];

    protected static function booted()
    {
        static::addGlobalScope(
            new TenantScope(app(TenantContextService::class))
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'ativo';
    }

    public function isAdmin(): bool
    {
        return $this->type === 'owner';
    }

    public function isFuncionario(): bool
    {
        return $this->type === 'funcionario';
    }

    public function isCliente(): bool
    {
        return $this->type === 'cliente';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ativo');
    }
}

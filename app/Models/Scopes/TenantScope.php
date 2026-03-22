<?php

namespace App\Models\Scopes;

use App\Services\TenantContextService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    public function __construct(
        protected TenantContextService $tenantContext
    ) {}

    public function apply(Builder $builder, Model $model): void
    {
        $tenant = $this->tenantContext->currentTenant();
        $user = Auth::user();

        if ($user?->hasRole('super-admin') || $user?->can('super-admin')) {
            return;
        }

        if ($tenant) {
            $builder->where('tenant_id', $tenant->id);
        }
    }
}

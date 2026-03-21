<?php

namespace App\Policies;

use App\Models\MemberProfile;
use App\Models\Tenant;
use App\Models\User;

class BillingPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    // GET /api/billing/status, /invoices, /portal
    public function view(User $user, Tenant $tenant): bool
    {
        return $this->isMemberOfTenant($user, $tenant)
            && $user->can('billing.view');
    }

    // POST /api/billing/subscription/checkout, /swap, /credits, /once
    public function manage(User $user, Tenant $tenant): bool
    {
        return $this->isOwnerOrAdmin($user, $tenant)
            && $user->can('billing.manage');
    }

    // POST /api/billing/subscription/cancel
    public function cancel(User $user, Tenant $tenant): bool
    {
        return $this->isOwner($user, $tenant)
            && $user->can('billing.cancel');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------
    private function isMemberOfTenant(User $user, Tenant $tenant): bool
    {
        return MemberProfile::where('user_id', $user->id)
            ->where('tenant_id', $tenant->id)
            ->where('status', 'ativo')
            ->exists();
    }

    private function isOwnerOrAdmin(User $user, Tenant $tenant): bool
    {
        return MemberProfile::where('user_id', $user->id)
            ->where('tenant_id', $tenant->id)
            ->whereIn('type', ['owner', 'admin'])
            ->where('status', 'ativo')
            ->exists();
    }

    private function isOwner(User $user, Tenant $tenant): bool
    {
        return MemberProfile::where('user_id', $user->id)
            ->where('tenant_id', $tenant->id)
            ->where('type', 'owner')
            ->where('status', 'ativo')
            ->exists();
    }
}

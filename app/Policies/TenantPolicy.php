<?php

namespace App\Policies;

use App\Models\MemberProfile;
use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    // Bypassa tudo para super-admin
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    // GET /api/tenant — listar só tenants que o usuário é membro
    public function viewAny(User $user): bool
    {
        return true; // filtrado no TenantService
    }

    // GET /api/tenant/current
    public function view(User $user, Tenant $tenant): bool
    {
        return $this->isMember($user, $tenant);
    }

    // POST /api/tenant — qualquer usuário autenticado pode criar
    public function create(User $user): bool
    {
        return true;
    }

    // PUT/PATCH /api/tenant/{tenant}
    public function update(User $user, Tenant $tenant): bool
    {
        return $this->isOwnerOrAdmin($user, $tenant)
            && $user->can('tenant.update');
    }

    // DELETE /api/tenant/{tenant}
    public function delete(User $user, Tenant $tenant): bool
    {
        return $this->isOwner($user, $tenant)
            && $user->can('tenant.delete');
    }

    // POST /api/tenant/switch/{tenant}
    public function switch(User $user, Tenant $tenant): bool
    {
        return $this->isMember($user, $tenant)
            && $user->can('tenant.switch');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------
    private function isMember(User $user, Tenant $tenant): bool
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

<?php

namespace App\Policies;

use App\Models\Invite;
use App\Models\MemberProfile;
use App\Models\User;

class InvitePolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    // GET /api/tenant/invites
    public function viewAny(User $user): bool
    {
        return $user->can('user.view');
    }

    // POST /api/tenant/invites — somente quem tem user.invite
    public function create(User $user): bool
    {
        return $user->can('user.invite');
    }

    // DELETE /api/tenant/invites/{invite}
    // Somente quem criou o convite ou owner/admin do tenant
    public function delete(User $user, Invite $invite): bool
    {
        if ($invite->invited_by === $user->id) {
            return true;
        }

        return MemberProfile::where('user_id', $user->id)
            ->where('tenant_id', $invite->tenant_id)
            ->whereIn('type', ['owner', 'admin'])
            ->where('status', 'ativo')
            ->exists();
    }
}

<?php

namespace App\Services;

use App\Models\Invite;
use App\Models\MemberProfile;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\InviteNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InviteService
{
    // -------------------------------------------------------
    // ENVIAR CONVITE
    // -------------------------------------------------------
    public function send(Tenant $tenant, User $invitedBy, string $email, string $role = 'member', string $type = 'member'): Invite
    {
        // Verifica se já é membro do tenant
        $alreadyMember = MemberProfile::where('tenant_id', $tenant->id)
            ->whereHas('user', fn($q) => $q->where('email', $email))
            ->exists();

        if ($alreadyMember) {
            throw ValidationException::withMessages([
                'email' => 'Este usuário já é membro deste tenant.',
            ]);
        }

        // Cancela convite anterior pendente se existir
        Invite::where('tenant_id', $tenant->id)
            ->where('email', $email)
            ->where('status', 'pending')
            ->update(['status' => 'canceled']);

        // Cria novo convite
        $invite = Invite::create([
            'tenant_id'  => $tenant->id,
            'invited_by' => $invitedBy->id,
            'email'      => $email,
            'token'      => Str::random(64),
            'role'       => $role,
            'type'       => $type,
            'status'     => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        // Envia notificação por email
        $invite->notify(new InviteNotification($invite));

        return $invite;
    }

    // -------------------------------------------------------
    // ACEITAR CONVITE — usuário já cadastrado
    // -------------------------------------------------------
    public function accept(string $token, User $user): Invite
    {
        $invite = $this->findValidOrFail($token);

        if ($invite->email !== $user->email) {
            throw ValidationException::withMessages([
                'token' => 'Este convite não pertence ao seu e-mail.',
            ]);
        }

        $this->attachUserToTenant($user, $invite);

        return $invite;
    }

    // -------------------------------------------------------
    // ACEITAR CONVITE — novo usuário (se registra pelo convite)
    // -------------------------------------------------------
    public function acceptAsNewUser(string $token, array $data): array
    {
        $invite = $this->findValidOrFail($token);

        if ($invite->email !== $data['email']) {
            throw ValidationException::withMessages([
                'email' => 'O e-mail não corresponde ao convite.',
            ]);
        }

        // Cria o usuário
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $this->attachUserToTenant($user, $invite);

        // Define tenant do convite como default
        $user->update(['default_tenant_id' => $invite->tenant_id]);

        return ['user' => $user, 'invite' => $invite];
    }

    // -------------------------------------------------------
    // CANCELAR CONVITE
    // -------------------------------------------------------
    public function cancel(Invite $invite, User $requestedBy): bool
    {
        // Somente quem convidou ou admin do tenant pode cancelar
        $isAdmin = MemberProfile::where('tenant_id', $invite->tenant_id)
            ->where('user_id', $requestedBy->id)
            ->whereIn('type', ['owner', 'admin'])
            ->exists();

        if ($invite->invited_by !== $requestedBy->id && !$isAdmin) {
            throw ValidationException::withMessages([
                'invite' => 'Você não tem permissão para cancelar este convite.',
            ]);
        }

        $invite->update(['status' => 'canceled']);
        return true;
    }

    // -------------------------------------------------------
    // LISTAR CONVITES DO TENANT
    // -------------------------------------------------------
    public function listByTenant(Tenant $tenant)
    {
        return Invite::where('tenant_id', $tenant->id)
            ->with('invitedBy:id,name,email')
            ->orderByDesc('created_at')
            ->get();
    }

    // -------------------------------------------------------
    // HELPERS PRIVADOS
    // -------------------------------------------------------
    private function findValidOrFail(string $token): Invite
    {
        $invite = Invite::where('token', $token)->first();

        if (!$invite || !$invite->isPending()) {
            throw ValidationException::withMessages([
                'token' => 'Convite inválido, expirado ou já utilizado.',
            ]);
        }

        return $invite;
    }

    private function attachUserToTenant(User $user, Invite $invite): void
    {
        // Cria MemberProfile no tenant
        MemberProfile::firstOrCreate(
            ['user_id' => $user->id, 'tenant_id' => $invite->tenant_id],
            ['type' => $invite->type, 'status' => 'ativo']
        );

        // Atribui role Spatie
        if (!$user->hasRole($invite->role)) {
            $user->assignRole($invite->role);
        }

        // Marca convite como aceito
        $invite->update([
            'status'      => 'accepted',
            'accepted_at' => now(),
        ]);
    }
}

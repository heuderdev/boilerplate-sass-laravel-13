<?php

namespace App\Services;

use App\Models\MemberProfile;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantContextService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected TenantContextService $tenantContext
    ) {}

    // -------------------------------------------------------
    // LOGIN — Monolito + API + Mobile
    // -------------------------------------------------------
    public function login(string $email, string $password, bool $remember = false): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        // Monolito: cria sessão
        Auth::login($user, $remember);

        // Carrega tenant default na sessão
        $this->tenantContext->setDefaultTenantAsCurrent();

        // API/Mobile: gera token vinculado ao tenant default
        $token = null;
        if ($user->hasDefaultTenant()) {
            $token = $user->createToken("tenant_{$user->default_tenant_id}")->plainTextToken;
        }

        return [
            'user'  => $user->load('defaultTenant'),
            'token' => $token,
        ];
    }

    // -------------------------------------------------------
    // REGISTRO — Cria user + tenant + memberProfile + default
    // -------------------------------------------------------
    public function register(array $data): array
    {
        // 1. Cria o usuário
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $tenantName = $this->generateUniqueTenantName($data['name']);

        // 2. Cria o tenant da empresa
        $tenant = Tenant::create([
            'name' => $tenantName,
            'slug' => Str::slug($tenantName),
        ]);

        // 3. Cria MemberProfile como admin do tenant
        MemberProfile::create([
            'user_id'   => $user->id,
            'tenant_id' => $tenant->id,
            'type'      => 'admin',
            'status'    => 'ativo',
        ]);

        // 4. Define tenant como default do usuário
        $user->update(['default_tenant_id' => $tenant->id]);
        $user->refresh();

        // 5. Loga e seta contexto
        Auth::login($user);
        $this->tenantContext->setCurrentTenant($tenant);

        // 6. Gera token vinculado ao tenant (API/Mobile)
        $token = $user->createToken("tenant_{$tenant->id}")->plainTextToken;

        return [
            'user'   => $user->load('defaultTenant'),
            'tenant' => $tenant,
            'token'  => $token,
        ];
    }

    private function generateUniqueTenantName(string $userName): string
    {
        $base  = Str::slug($userName);
        $name  = $base;
        $count = 2;

        while (Tenant::where('slug', Str::slug($name))->exists()) {
            $name = "{$base}-{$count}";
            $count++;
        }

        return $name;
    }

    // -------------------------------------------------------
    // TROCAR TENANT — Gera novo token p/ o tenant escolhido
    // -------------------------------------------------------
    public function switchTenant(Tenant $tenant): array
    {
        $user = Auth::user();

        $switched = $this->tenantContext->switchTo($tenant);

        if (!$switched) {
            throw ValidationException::withMessages([
                'tenant' => 'Você não tem acesso a este tenant.',
            ]);
        }

        $user->setDefaultTenant($tenant);

        // Revoga token anterior e gera novo vinculado ao tenant
        $user->currentAccessToken()?->delete();
        $token = $user->createToken("tenant_{$tenant->id}")->plainTextToken;

        return [
            'tenant' => $tenant,
            'token'  => $token,
        ];
    }

    // -------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------
    public function logout(): void
    {
        $user = Auth::user();

        // Revoga token atual (API/Mobile)
        $user?->currentAccessToken()?->delete();

        // Limpa contexto de tenant
        $this->tenantContext->clear();

        // Encerra sessão (monolito)
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}

<?php

namespace Database\Seeders;

use App\Models\MemberProfile;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache de permissões antes de atribuir roles
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------
        // SUPER ADMIN — usuário global que bypassa todo o Gate
        // -------------------------------------------------------
        $superAdmin = User::firstOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL', 'programadorwebti@gmail.com')],
            [
                'name'              => env('SUPER_ADMIN_NAME', 'HEUDER DEV'),
                'password'          => Hash::make(env('SUPER_ADMIN_PASSWORD', '12345678')),
                'email_verified_at' => now(),
            ]
        );

        $superAdmin->assignRole('super-admin');

        // -------------------------------------------------------
        // TENANT PADRÃO DO SISTEMA (para o super admin operar)
        // -------------------------------------------------------
        $tenant = Tenant::firstOrCreate(
            ['name' => env('DEFAULT_TENANT_NAME', 'Acme Corp')],
            ['credits_balance' =>  0, 'slug' => Str::uuid()]
        );

        // Vincula super admin ao tenant como owner
        MemberProfile::firstOrCreate(
            ['user_id' => $superAdmin->id, 'tenant_id' => $tenant->id],
            ['type' => 'owner', 'status' => 'ativo']
        );

        // Define tenant padrão do super admin
        $superAdmin->update(['default_tenant_id' => $tenant->id]);

        // -------------------------------------------------------
        // SYSTEM ADMIN — para testes sem bypassar o Gate
        // -------------------------------------------------------
        $systemAdmin = User::firstOrCreate(
            ['email' => env('SYSTEM_ADMIN_EMAIL', 'admin@app.com')],
            [
                'name'              => env('SYSTEM_ADMIN_NAME', 'System Admin'),
                'password'          => Hash::make(env('SYSTEM_ADMIN_PASSWORD', 'password')),
                'email_verified_at' => now(),
            ]
        );

        $systemAdmin->assignRole('system-admin');

        MemberProfile::firstOrCreate(
            ['user_id' => $systemAdmin->id, 'tenant_id' => $tenant->id],
            ['type' => 'owner', 'status' => 'ativo']
        );

        $systemAdmin->update(['default_tenant_id' => $tenant->id]);

        $this->command->info('✅ Super Admin criado: ' . $superAdmin->email);
        $this->command->info('✅ System Admin criado: ' . $systemAdmin->email);
        $this->command->info('✅ Tenant padrão: ' . $tenant->name);
    }
}

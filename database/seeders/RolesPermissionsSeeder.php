<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache do Spatie antes de tudo
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------
        // PERMISSIONS GENÉRICAS
        // -------------------------------------------------------
        $permissions = [
            // Usuários
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'user.invite',

            // Tenant
            'tenant.view',
            'tenant.create',
            'tenant.update',
            'tenant.delete',
            'tenant.switch',

            // Billing
            'billing.view',
            'billing.manage',
            'billing.cancel',

            // Roles/Permissions
            'role.view',
            'role.assign',

            // Conteúdo
            'content.view',
            'content.create',
            'content.update',
            'content.delete',

            // Relatórios
            'report.view',
            'report.export',

            // Sistema (somente globais)
            'system.manage',
            'system.audit',
            'system.support',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // -------------------------------------------------------
        // ROLES GLOBAIS (fora do tenant)
        // -------------------------------------------------------

        // super-admin: bypassa tudo via Gate — NÃO precisa de permissions
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        // system-admin: gerencia o sistema mas não bypassa o Gate
        $systemAdmin = Role::firstOrCreate(['name' => 'system-admin', 'guard_name' => 'web']);
        $systemAdmin->syncPermissions([
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'user.invite',
            'tenant.view',
            'tenant.create',
            'tenant.update',
            'tenant.delete',
            'billing.view',
            'billing.manage',
            'billing.cancel',
            'role.view',
            'role.assign',
            'content.view',
            'content.create',
            'content.update',
            'content.delete',
            'report.view',
            'report.export',
            'system.manage',
        ]);

        // support: resolve problemas de usuários
        $support = Role::firstOrCreate(['name' => 'support', 'guard_name' => 'web']);
        $support->syncPermissions([
            'user.view',
            'user.update',
            'tenant.view',
            'billing.view',
            'content.view',
            'report.view',
            'system.support',
        ]);

        // auditor: somente leitura total
        $auditor = Role::firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);
        $auditor->syncPermissions([
            'user.view',
            'tenant.view',
            'billing.view',
            'content.view',
            'report.view',
            'report.export',
            'system.audit',
        ]);

        // -------------------------------------------------------
        // ROLES DE TENANT
        // -------------------------------------------------------

        // owner: dono do tenant — tudo dentro do tenant
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $owner->syncPermissions([
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'user.invite',
            'tenant.view',
            'tenant.update',
            'billing.view',
            'billing.manage',
            'billing.cancel',
            'role.view',
            'role.assign',
            'content.view',
            'content.create',
            'content.update',
            'content.delete',
            'report.view',
            'report.export',
        ]);

        // admin: administra o tenant mas não cancela billing
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'user.view',
            'user.create',
            'user.update',
            'user.invite',
            'tenant.view',
            'tenant.update',
            'billing.view',
            'role.view',
            'role.assign',
            'content.view',
            'content.create',
            'content.update',
            'content.delete',
            'report.view',
            'report.export',
        ]);

        // manager: gerente de área
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'user.view',
            'user.invite',
            'tenant.view',
            'content.view',
            'content.create',
            'content.update',
            'content.delete',
            'report.view',
            'report.export',
        ]);

        // member: colaborador comum
        $member = Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);
        $member->syncPermissions([
            'tenant.view',
            'content.view',
            'content.create',
            'content.update',
            'report.view',
        ]);

        // contributor: foco em criar/editar conteúdo
        $contributor = Role::firstOrCreate(['name' => 'contributor', 'guard_name' => 'web']);
        $contributor->syncPermissions([
            'tenant.view',
            'content.view',
            'content.create',
            'content.update',
        ]);

        // viewer: somente leitura
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions([
            'tenant.view',
            'content.view',
            'report.view',
        ]);

        // guest: acesso mínimo
        $guest = Role::firstOrCreate(['name' => 'guest', 'guard_name' => 'web']);
        $guest->syncPermissions([
            'tenant.view',
            'report.view',
        ]);

        $this->command->info('✅ Roles e permissions criadas com sucesso.');
    }
}

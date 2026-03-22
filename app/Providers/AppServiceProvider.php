<?php

namespace App\Providers;

use App\Models\Invite;
use App\Models\Tenant;
use App\Policies\BillingPolicy;
use App\Policies\InvitePolicy;
use App\Policies\TenantPolicy;
use App\Services\AuthService;
use App\Services\InviteService;
use App\Services\TenantBillingService;
use App\Services\TenantContextService;
use App\Services\TenantService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Tenant::class => TenantPolicy::class,
        Invite::class => InvitePolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantContextService::class);

        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService(
                $app->make(TenantContextService::class)
            );
        });

        // TENANTBILLINGSERVICE NÃO TEM DEPENDÊNCIAS → AUTOWIRING DIRETO
        $this->app->singleton(TenantBillingService::class);

        $this->app->singleton(TenantService::class);

        $this->app->singleton(InviteService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useCustomerModel(Tenant::class);
        // $this->registerPolicies();

        // BillingPolicy não tem model próprio — registra manualmente
        Gate::define('billing.view',   [BillingPolicy::class, 'view']);
        Gate::define('billing.manage', [BillingPolicy::class, 'manage']);
        Gate::define('billing.cancel', [BillingPolicy::class, 'cancel']);

        // super-admin bypassa todo o Gate via Spatie
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}

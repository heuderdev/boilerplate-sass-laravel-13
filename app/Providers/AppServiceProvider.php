<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\InviteService;
use App\Services\TenantBillingService;
use App\Services\TenantContextService;
use App\Services\TenantService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        //
    }
}

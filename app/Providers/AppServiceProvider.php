<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\TenantContextService;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Billing\BillingController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\Tenant\InviteController;
use App\Http\Controllers\Api\Tenant\TenantController;
use App\Http\Controllers\Api\Tenant\TenantSwitchController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// Webhook Stripe — sem auth, sem CSRF
// -------------------------------------------------------
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('cashier.webhook');

// -------------------------------------------------------
// Públicas
// -------------------------------------------------------
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login',    [AuthController::class, 'login'])->name('login');
});

// Aceitar convite como novo usuário — sem auth
Route::post('/invites/{token}/accept-new', [InviteController::class, 'acceptAsNewUser'])
    ->name('invites.accept-new');

// -------------------------------------------------------
// Protegidas
// -------------------------------------------------------
Route::middleware(['auth:sanctum', 'tenant.set'])->group(function () {

    // Auth
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me',      [AuthController::class, 'me'])->name('me');
    });

    // Tenant
    Route::prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/',                      [TenantController::class, 'index'])->name('index');
        Route::get('/current',               [TenantController::class, 'current'])->name('current');
        Route::post('/',                     [TenantController::class, 'store'])->name('store');
        Route::post('/switch/{tenant}',      [TenantSwitchController::class, '__invoke'])->name('switch');
        Route::patch('/{tenant}/default',    [TenantController::class, 'setDefault'])->name('set-default');

        // Invites — dentro do contexto de tenant
        Route::get('/invites',               [InviteController::class, 'index'])->name('invites.index');
        Route::post('/invites',              [InviteController::class, 'store'])->name('invites.store');
        Route::delete('/invites/{invite}',   [InviteController::class, 'destroy'])->name('invites.destroy');
    });

    // Aceitar convite — usuário já logado
    Route::post('/invites/{token}/accept', [InviteController::class, 'accept'])
        ->name('invites.accept');

    // Billing
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/status',                      [BillingController::class, 'status'])->name('status');
        Route::get('/portal',                      [BillingController::class, 'portal'])->name('portal');
        Route::get('/invoices',                    [BillingController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{invoice}/download', [BillingController::class, 'downloadInvoice'])->name('invoices.download');
        Route::post('/subscription/checkout',      [BillingController::class, 'subscriptionCheckout'])->name('subscription.checkout');
        Route::post('/subscription/cancel',        [BillingController::class, 'cancelSubscription'])->name('subscription.cancel');
        Route::post('/subscription/resume',        [BillingController::class, 'resumeSubscription'])->name('subscription.resume');
        Route::post('/subscription/swap',          [BillingController::class, 'swapPlan'])->name('subscription.swap');
        Route::post('/credits/checkout',           [BillingController::class, 'creditsCheckout'])->name('credits.checkout');
        Route::post('/once/checkout',              [BillingController::class, 'onceCheckout'])->name('once.checkout');
    });
});

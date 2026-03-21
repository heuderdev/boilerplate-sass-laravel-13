<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Billing\BillingController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\Tenant\TenantController;
use App\Http\Controllers\Api\Tenant\TenantSwitchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('cashier.webhook');


Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login',    [AuthController::class, 'login'])->name('login');
});



Route::middleware(['auth:sanctum', 'tenant.set'])->group(function () {

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me',      [AuthController::class, 'me'])->name('me');
    });

    Route::prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/',           [TenantController::class, 'index'])->name('index');
        Route::get('/current',    [TenantController::class, 'current'])->name('current');
        Route::post('/',          [TenantController::class, 'store'])->name('store');
        Route::post('/switch/{tenant}', [TenantSwitchController::class, '__invoke'])->name('switch');
    });

    // Billing
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/status',                        [BillingController::class, 'status'])->name('status');
        Route::get('/portal',                        [BillingController::class, 'portal'])->name('portal');
        Route::get('/invoices',                      [BillingController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{invoice}/download',   [BillingController::class, 'downloadInvoice'])->name('invoices.download');
        Route::post('/subscription/checkout',        [BillingController::class, 'subscriptionCheckout'])->name('subscription.checkout');
        Route::post('/subscription/cancel',          [BillingController::class, 'cancelSubscription'])->name('subscription.cancel');
        Route::post('/subscription/resume',          [BillingController::class, 'resumeSubscription'])->name('subscription.resume');
        Route::post('/subscription/swap',            [BillingController::class, 'swapPlan'])->name('subscription.swap');
        Route::post('/credits/checkout',             [BillingController::class, 'creditsCheckout'])->name('credits.checkout');
        Route::post('/once/checkout',                [BillingController::class, 'onceCheckout'])->name('once.checkout');
    });
});

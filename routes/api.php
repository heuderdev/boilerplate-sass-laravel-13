<?php

use App\Http\Controllers\Api\Auth\AuthController;
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

    // Billing (virá a seguir)
    // Route::prefix('billing')->name('billing.')->group(function () { ... });
});

<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\StripeWebhookController;
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

    // AUTH
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me',      [AuthController::class, 'me'])->name('me');
    });

    // Tenant (switch — virá a seguir)
    // Route::prefix('tenant')->name('tenant.')->group(function () { ... });

    // Billing (virá a seguir)
    // Route::prefix('billing')->name('billing.')->group(function () { ... });
});

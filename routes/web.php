<?php

use App\Http\Controllers\LandingPageController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\DashboardPage;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing-page.index');

Route::middleware('guest')->group(function () {
    Route::get('/login',    Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::get('/dashboard', DashboardPage::class)->name('dashboard');

Route::get('/billing/success', fn() => view('billing.success'))->name('billing.success');
Route::get('/billing/cancel',  fn() => view('billing.cancel'))->name('billing.cancel');

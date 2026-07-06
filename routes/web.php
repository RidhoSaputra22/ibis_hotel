<?php

use App\Http\Controllers\SystemAccessController;
use App\Http\Controllers\CashierLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SystemAccessController::class, 'entry'])->name('system.entry');
Route::get('/system/login', [SystemAccessController::class, 'create'])->name('system.login');
Route::post('/system/login', [SystemAccessController::class, 'store'])->name('system.login.store');

Route::middleware('system.login')->group(function (): void {
    Route::get('/cashier/open', [CashierLoginController::class, 'create'])->name('cashier.session.create');
    Route::post('/cashier-login', [CashierLoginController::class, 'store'])->name('cashier.login.store');
});

Route::middleware('cashier.session')->group(function (): void {
    Route::view('/dashboard', 'restaurant-transaction.menu-section')->name('cashier.dashboard');
    Route::view('/restaurant-transaction', 'restaurant-transaction.transaction')->name('restaurant.transaction');
    Route::view('/table-list', 'restaurant-transaction.table-list')->name('restaurant.table-list');
    Route::view('/daily-cashier-summary', 'daily-cashier-summary.summary')->name('daily-cashier.summary');
    Route::view('/daily-cashier-summary/print', 'daily-cashier-summary.summary-print')->name('daily-cashier.summary.print');
});

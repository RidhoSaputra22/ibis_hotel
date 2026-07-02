<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'restaurant-transaction-section.menu-section')->name('cashier.dashboard');
Route::view('/restaurant-transaction', 'restaurant-transaction-section.transaction')->name('restaurant.transaction');
Route::view('/table-list', 'restaurant-transaction-section.table-list')->name('restaurant.table-list');
Route::view('/daily-cashier-summary', 'daily-cashier-section.daily-cashier-summary')->name('daily-cashier.summary');
Route::view('/daily-cashier-summary/print', 'daily-cashier-section.daily-cashier-summary-print')->name('daily-cashier.summary.print');

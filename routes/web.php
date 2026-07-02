<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'restaurant-transaction.menu-section')->name('cashier.dashboard');
Route::view('/restaurant-transaction', 'restaurant-transaction.transaction')->name('restaurant.transaction');
Route::view('/table-list', 'restaurant-transaction.table-list')->name('restaurant.table-list');
Route::view('/daily-cashier-summary', 'daily-cashier-summary.summary')->name('daily-cashier.summary');
Route::view('/daily-cashier-summary/print', 'daily-cashier-summary.summary-print')->name('daily-cashier.summary.print');

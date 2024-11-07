<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/customer/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('customer.orders.index');
Route::get('/customer/orders/{order}', [App\Http\Controllers\Customer\OrderController::class, 'show'])->name('customer.orders.show');

Route::post('/payment/midtrans-callback', [App\Http\Controllers\PaymentController::class, 'midtransCallback']);

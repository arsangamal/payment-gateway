<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Order\CreateOrderController;
use App\Http\Controllers\Order\DeleteOrderController;
use App\Http\Controllers\Order\ListOrdersController;
use App\Http\Controllers\Order\ReadOrderController;
use App\Http\Controllers\Order\UpdateOrderController;
use App\Http\Controllers\Payment\ListPaymentsController;
use App\Http\Controllers\Payment\PayController;
use App\Http\Controllers\Payment\ReadPaymentController;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class);
Route::post('register', RegisterController::class);


Route::middleware('auth')->group(function () {
    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', ListOrdersController::class);
        Route::post('/', CreateOrderController::class);
        Route::get('/{order}', ReadOrderController::class);
        Route::put('/{order}', UpdateOrderController::class);
        Route::delete('/{order}', DeleteOrderController::class);
        Route::get('/{order}/confirm', \App\Http\Controllers\Order\ConfirmOrderController::class);
    });

    // Payment routes
    Route::prefix('payments')->group(function () {
        Route::post('/{order}/pay', PayController::class);
        Route::get('/', ListPaymentsController::class);
        Route::get('/{payment}', ReadPaymentController::class);
    });
});

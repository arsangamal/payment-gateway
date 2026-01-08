<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Order\CreateOrderController;
use App\Http\Controllers\Order\DeleteOrderController;
use App\Http\Controllers\Order\ListOrdersController;
use App\Http\Controllers\Order\ReadOrderController;
use App\Http\Controllers\Order\UpdateOrderController;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class);
Route::post('register', RegisterController::class);


Route::middleware('auth')->prefix('orders')->group(function () {
    Route::get('/', ListOrdersController::class);
    Route::post('/', CreateOrderController::class);
    Route::get('/{order}', ReadOrderController::class);
    Route::put('/{order}', UpdateOrderController::class);
    Route::delete('/{order}', DeleteOrderController::class);
});

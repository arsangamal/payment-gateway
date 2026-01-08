<?php

namespace App\Providers;

use App\Interfaces\IOrderRepository;
use App\Interfaces\IPaymentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IOrderRepository::class, OrderRepository::class);

        $this->app->bind(OrderService::class, function ($app) {
            return new OrderService($app->make(IOrderRepository::class));
        });


        $this->app->bind(IPaymentRepository::class, PaymentRepository::class);

        $this->app->bind(PaymentService::class, function ($app) {
            return new PaymentService($app->make(IPaymentRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

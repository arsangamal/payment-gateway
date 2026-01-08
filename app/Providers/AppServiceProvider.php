<?php

namespace App\Providers;

use App\Interfaces\IOrderRepository;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

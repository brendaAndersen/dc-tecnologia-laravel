<?php

namespace App\Providers;

use App\Services\ClientService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   public function register()
    {
        $this->app->bind(ClientService::class, function ($app) {
            return new ClientService();
        });
        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService();
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

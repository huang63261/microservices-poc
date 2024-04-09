<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('inventory', function () {
            return Http::baseUrl(config('services.ms_inventory.api_base_url'));
        });

        Http::macro('order', function () {
            return Http::baseUrl(config('services.ms_order.api_base_url'));
        });

        Http::macro('payment', function () {
            return Http::baseUrl(config('services.ms_payment.api_base_url'));
        });

        Http::macro('product', function () {
            return Http::baseUrl(config('services.ms_product.api_base_url'));
        });
    }
}

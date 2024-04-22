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
        Http::macro('ms_product', function () {
            return Http::baseUrl(config('services.ms_product.api_base_url'));
        });

        Http::macro('ms_review', function () {
            return Http::baseUrl(config('services.ms_review.api_base_url'));
        });

        Http::macro('ms_photo', function () {
            return Http::baseUrl(config('services.ms_photo.api_base_url'));
        });

        Http::macro('ms_inventory', function () {
            return Http::baseUrl(config('services.ms_inventory.api_base_url'));
        });
    }
}

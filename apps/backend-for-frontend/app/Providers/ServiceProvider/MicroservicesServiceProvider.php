<?php

namespace App\Providers\ServiceProvider;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class MicroservicesServiceProvider extends ServiceProvider
{
    protected $services = [
        'InventoryService' => 'ms_inventory',
        'PhotoService' => 'ms_photo',
        'ProductService' => 'ms_product',
        'ReviewService' => 'ms_review',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach ($this->services as $service => $configKey) {
            $this->app->bind("App\Services\Contracts\\{$service}", function () use ($configKey, $service) {
                $baseUrl = config("services.{$configKey}.base_url");
                $httpRequest = env('APP_ENV') === 'production'
                    ? new \App\Services\Http\GoogleCloudApiRequest($baseUrl)
                    : new \App\Services\Http\HttpRequest(Http::{$configKey}());

                $serviceClass = env('APP_ENV') === 'production'
                    ? "\App\Services\Gcp\\{$service}"
                    : "\App\Services\\{$service}";

                return new $serviceClass($httpRequest);
            });
        }
    }
}
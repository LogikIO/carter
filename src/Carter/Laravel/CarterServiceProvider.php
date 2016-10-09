<?php

namespace NickyWoolf\Carter\Laravel;

use Illuminate\Support\ServiceProvider;
use NickyWoolf\Carter\Shopify\Client;
use NickyWoolf\Carter\Shopify\Domain;
use NickyWoolf\Carter\Shopify\Signature;
use Route;

class CarterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/routes.php' => base_path('routes/carter.php'),
            __DIR__.'/config.php' => config_path('carter.php'),
            __DIR__.'/views'      => resource_path('views/vendor/carter'),
        ]);

        if (! $this->app->routesAreCached()) {
            $this->mapRoutes();
        }
    }

    protected function mapRoutes()
    {
        if (! file_exists(base_path('routes/carter.php'))) {
            return;
        }

        Route::group([
            'middleware' => 'web',
        ], function ($router) {
            require base_path('routes/carter.php');
        });
    }

    public function register()
    {
        $this->app->when(Domain::class)->needs('$domain')->give(function () {
            return $this->domain();
        });

        $this->app->when(Client::class)->needs('$accessToken')->give(function () {
            return $this->accessToken();
        });

        $this->app->when(Signature::class)->needs('$request')->give(function () {
            return $this->request();
        });
    }

    protected function domain()
    {
        return auth()->check() ? auth()->user()->domain : request('shop');
    }

    protected function accessToken()
    {
        return auth()->check() ? auth()->user()->access_token : null;
    }

    protected function request()
    {
        return request()->all();
    }
}

<?php

namespace Woolf\Carter;

use Crypt;
use Illuminate\Support\ServiceProvider;
use Woolf\Carter\Shopify\Shopify;
use Woolf\Shophpify\Client;
use Woolf\Shophpify\Endpoint;
use Woolf\Shophpify\Signature;

class CarterServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadViewsFrom($this->packageViews(), 'carter');
        $this->publishes([$this->packageViews() => base_path('resources/views/vendor/carter')]);

        $this->publishes([$this->packageConfig() => config_path('carter.php')], 'config');
        $this->mergeConfigFrom($this->packageConfig(), 'carter');

        $this->commands('command.carter.table');

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }
    }

    protected function packageViews()
    {
        return __DIR__.'/views';
    }

    protected function packageConfig()
    {
        return __DIR__.'/config/carter.php';
    }

    public function register()
    {
        $this->app->bind('carter_user', function ($app) {
            return $app->make($app->make('config')->get('auth.providers.users.model'));
        });

        $this->app->when(Endpoint::class)->needs('$domain')->give($this->myshopifyDomain());

        $this->app->when(Client::class)->needs('$accessToken')->give($this->accessToken());

        $this->app->when(Signature::class)->needs('$request')->give($this->requestArray());

        $this->app->singleton('command.carter.table', function () {
            return new CarterTableCommand();
        });
    }

    protected function myshopifyDomain()
    {
        return function () {
            return ($user = auth()->user()) ? $user->domain : request('shop');
        };
    }

    protected function accessToken()
    {
        return function () {
            return (auth()->check()) ? auth()->user()->access_token : null;
        };
    }

    protected function requestArray()
    {
        return function () {
            return request()->all();
        };
    }
}
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
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__.'/views', 'carter');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/carter'),
        ]);

        $this->publishes([
            __DIR__.'/config/carter.php' => config_path('carter.php')
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/config/carter.php', 'carter');

        $this->commands('command.carter.table');
    }

    public function register()
    {
        $this->app->bind('carter_user', function ($app) {
            return $app->make($app->make('config')->get('auth.providers.users.model'));
        });

        $this->app->when(Endpoint::class)
            ->needs('$domain')
            ->give(function () {
                return ($user = auth()->user()) ? $user->domain : request('shop');
            });

        $this->app->when(Client::class)
            ->needs('$accessToken')
            ->give(function () {
                return (auth()->check()) ? auth()->user()->access_token : null;
            });

        $this->app->when(Signature::class)
            ->needs('$request')
            ->give(function () {
                return request()->all();
            });

        $this->app->singleton('command.carter.table', function () {
            return new CarterTableCommand();
        });
    }
}
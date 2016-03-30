<?php

namespace Woolf\Carter;

use Crypt;
use Illuminate\Support\ServiceProvider;
use Woolf\Carter\Shopify\ShopUrl;

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

    }

    public function register()
    {
        $this->app->bind('carter.auth.model', function ($app) {
            return $app->make($app->make('config')->get('auth.providers.users.model'));
        });

        $this->app->bind(ShopUrl::class, function ($app) {
            $domain = ($user = $app['auth']->user()) ? $user->domain : $app['request']->input('shop');
            return new ShopUrl($domain);
        });

        $this->mergeConfigFrom(__DIR__.'/config/carter.php', 'carter');

        $this->app->singleton('command.carter.table', function () {
            return new CarterTableCommand();
        });

        $this->commands('command.carter.table');
    }
}
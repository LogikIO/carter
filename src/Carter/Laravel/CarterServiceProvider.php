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

        $this->loadViewsFrom(__DIR__.'/views', 'carter');
        $this->mergeConfigFrom(__DIR__.'/config.php', 'carter');
        $this->commands('command.carter.table');

        if (! $this->app->routesAreCached()) {
            $this->mapRoutes();
        }
    }

    protected function mapRoutes()
    {
        Route::group(['middleware' => 'web',], function ($router) {
            if (file_exists(base_path('routes/carter.php'))) {
                return require base_path('routes/carter.php');
            }

            require __DIR__.'/routes.php';
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

        $this->app->bind('carter.user', function ($app) {
            return app(app('config')->get('auth.providers.users.model'));
        });

        $this->app->singleton('command.carter.table', function () {
            return $this->tableCommand();
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

    protected function tableCommand()
    {
        return new CarterTableCommand();
    }
}

<?php

namespace NickyWoolf\Carter\Laravel;

use Illuminate\Support\ServiceProvider;
use NickyWoolf\Carter\Laravel\Middleware\Authenticate;
use NickyWoolf\Carter\Laravel\Middleware\RedirectIfAuthenticated;
use NickyWoolf\Carter\Laravel\Middleware\RequestHasChargeId;
use NickyWoolf\Carter\Laravel\Middleware\RequestHasShopDomain;
use NickyWoolf\Carter\Laravel\Middleware\RequestHasShopifySignature;
use NickyWoolf\Carter\Laravel\Middleware\VerifyChargeAccepted;
use NickyWoolf\Carter\Laravel\Middleware\VerifySignature;
use NickyWoolf\Carter\Laravel\Middleware\VerifyState;
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
        $this->registerMiddleware();

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

    protected function registerMiddleware()
    {
        $routeMiddleware = [
            'carter.auth'      => Authenticate::class,
            'carter.guest'     => RedirectIfAuthenticated::class,
            'carter.charged'   => RequestHasChargeId::class,
            'carter.domain'    => RequestHasShopDomain::class,
            'carter.signed'    => RequestHasShopifySignature::class,
            'carter.paying'    => VerifyChargeAccepted::class,
            'carter.signature' => VerifySignature::class,
            'carter.nonce'     => VerifyState::class,
        ];

        foreach ($routeMiddleware as $key => $middleware) {
            Route::middleware($key, $middleware);
        }
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

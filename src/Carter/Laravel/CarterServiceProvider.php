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
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/routes.php' => $this->routesPath(),
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

    /**
     * @return string
     */
    protected function routesPath()
    {
        return $this->hasRoutesDirectory() ? base_path('routes/carter.php') : app_path('Http/carter.php');
    }

    /**
     * @return void
     */
    protected function mapRoutes()
    {
        if ($this->hasRoutesDirectory() && file_exists(base_path('routes/carter.php'))) {
            return require base_path('routes/carter.php');
        } elseif (file_exists(app_path('Http/carter.php'))) {
            return require app_path('Http/carter.php');
        }

        require __DIR__.'/routes.php';
    }

    /**
     * @return bool
     */
    protected function hasRoutesDirectory()
    {
        return file_exists(base_path('routes/web.php'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
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

    /**
     * @return void
     */
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

    /**
     * @return array|\Illuminate\Http\Request|string
     */
    protected function domain()
    {
        return auth()->check() ? auth()->user()->domain : request('shop');
    }

    /**
     * @return null|string
     */
    protected function accessToken()
    {
        return auth()->check() ? auth()->user()->access_token : null;
    }

    /**
     * @return array
     */
    protected function request()
    {
        return request()->all();
    }

    /**
     * @return CarterTableCommand
     */
    protected function tableCommand()
    {
        return new CarterTableCommand();
    }
}

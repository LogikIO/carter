<?php

use Woolf\Carter\Http\Middleware\Authenticate;
use Woolf\Carter\Http\Middleware\RedirectIfAuthenticated;

Route::group(['middleware' => 'web'], function ($router) {

    $router->get(
        config('carter.shopify.routes.signup.uri'),
        config('carter.shopify.routes.signup.action')
    )->name('shopify.signup');

    $router->match(
        ['get', 'post'],
        config('carter.shopify.routes.install.uri'),
        config('carter.shopify.routes.install.action')
    )->name('shopify.install')->middleware(RedirectIfAuthenticated::class);

    $router->get(
        config('carter.shopify.routes.register.uri'),
        config('carter.shopify.routes.register.action')
    )->name('shopify.register')->middleware(RedirectIfAuthenticated::class);

    $router->get(
        config('carter.shopify.routes.activate.uri'),
        config('carter.shopify.routes.activate.action')
    )->name('shopify.activate');

    $router->get(
        config('carter.shopify.routes.login.uri'),
        config('carter.shopify.routes.login.action')
    )->name('shopify.login')->middleware(RedirectIfAuthenticated::class);

    $router->get(
        config('carter.shopify.routes.dashboard.uri'),
        config('carter.shopify.routes.dashboard.action')
    )->name('shopify.dashboard')->middleware(Authenticate::class);

});

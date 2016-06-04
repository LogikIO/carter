<?php

use Woolf\Carter\Http\Middleware\RedirectIfAuthenticated;
use Woolf\Carter\Http\Middleware\RequestHasChargeId;
use Woolf\Carter\Http\Middleware\RequestHasShopDomain;
use Woolf\Carter\Http\Middleware\RequestHasShopifySignature;
use Woolf\Carter\Http\Middleware\VerifySignature;
use Woolf\Carter\Http\Middleware\VerifyState;

Route::group(['middleware' => 'web'], function ($router) {

    $router->get(carter_route('signup.uri'), carter_route('signup.action'))
        ->name('shopify.signup');

    $router->match(['get', 'post'], carter_route('install.uri'), carter_route('install.action'))
        ->middleware([RedirectIfAuthenticated::class, RequestHasShopDomain::class])
        ->name('shopify.install');

    $router->get(carter_route('register.uri'), carter_route('register.action'))
        ->middleware([
            RedirectIfAuthenticated::class,
            RequestHasShopDomain::class,
            RequestHasShopifySignature::class,
            VerifyState::class,
            VerifySignature::class,
        ])
        ->name('shopify.register');

    $router->get(carter_route('activate.uri'), carter_route('activate.action'))
        ->middleware([RequestHasChargeId::class])
        ->name('shopify.activate');

    $router->get(carter_route('login.uri'), carter_route('login.action'))
        ->middleware([RedirectIfAuthenticated::class, RequestHasShopDomain::class, VerifySignature::class])
        ->name('shopify.login');

    $router->get(carter_route('dashboard.uri'), carter_route('dashboard.action'))
        ->name('shopify.dashboard');

});

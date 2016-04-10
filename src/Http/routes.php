<?php

Route::group(['middleware' => 'web'], function ($router) {

    $router->get(
        config('carter.shopify.routes.signup.url'),
        config('carter.shopify.routes.signup.action')
    )->name('shopify.signup');

    $router->match(
        ['get', 'post'],
        config('carter.shopify.routes.install.url'),
        config('carter.shopify.routes.install.action')
    )->name('shopify.install');

    $router->get(
        config('carter.shopify.routes.register.url'),
        config('carter.shopify.routes.register.action')
    )->name('shopify.register');

    $router->get(
        config('carter.shopify.routes.activate.url'),
        config('carter.shopify.routes.activate.action')
    )->name('shopify.activate');

    $router->get(
        config('carter.shopify.routes.login.url'),
        config('carter.shopify.routes.login.action')
    )->name('shopify.login');

    $router->get(
        config('carter.shopify.routes.dashboard.url'),
        config('carter.shopify.routes.dashboard.action')
    )->name('shopify.dashboard');

});

<?php

Route::get('shopify/signup', 'NickyWoolf\Carter\Laravel\ShopifyController@signupForm')
    ->name('shopify.signup');

Route::match(['get', 'post'], 'shopify/install', 'NickyWoolf\Carter\Laravel\ShopifyController@install')
    ->middleware(['carter.guest', 'carter.domain'])
    ->name('shopify.install');

Route::get('shopify/register', 'NickyWoolf\Carter\Laravel\ShopifyController@register')
    ->middleware(['carter.guest', 'carter.domain', 'carter.install', 'carter.nonce'])
    ->name('shopify.register');

Route::get('shopify/activate', 'NickyWoolf\Carter\Laravel\ShopifyController@activate')
    ->middleware(['carter.charged'])
    ->name('shopify.activate');

Route::get('shopify/login', 'NickyWoolf\Carter\Laravel\ShopifyController@login')
    ->middleware(['carter.guest', 'carter.domain', 'carter.signed'])
    ->name('shopify.login');

Route::get('shopify/login/redirect', 'NickyWoolf\Carter\Laravel\ShopifyController@login')
    ->middleware(['carter.guest', 'carter.domain', 'carter.signed', 'carter.sign'])
    ->name('shopify.login.redirect');

Route::get('shopify/dashboard', 'NickyWoolf\Carter\Laravel\ShopifyController@dashboard')
    ->middleware(['carter.signed', 'carter.auth', 'carter.paying'])
    ->name('shopify.dashboard');

Route::post('shopify/uninstall', 'NickyWoolf\Carter\Laravel\ShopifyController@uninstall')
    ->middleware(['carter.webhook'])
    ->name('shopify.uninstall');

<?php

Route::group(['middleware' => 'web'], function () {
    Route::get('shopify/signup', 'NickyWoolf\Carter\Laravel\ShopifyController@signupForm')
        ->name('shopify.signup');

    Route::match(['get', 'post'], 'shopify/install', 'NickyWoolf\Carter\Laravel\ShopifyController@install')
        ->middleware(['carter.guest', 'carter.domain'])
        ->name('shopify.install');

    Route::get('shopify/register', 'NickyWoolf\Carter\Laravel\ShopifyController@register')
        ->middleware(['carter.guest', 'carter.domain', 'carter.signed', 'carter.nonce', 'carter.signature'])
        ->name('shopify.register');

    Route::get('shopify/activate', 'NickyWoolf\Carter\Laravel\ShopifyController@activate')
        ->middleware(['carter.charged'])
        ->name('shopify.activate');

    Route::get('shopify/login', 'NickyWoolf\Carter\Laravel\ShopifyController@login')
        ->middleware(['carter.guest', 'carter.domain', 'carter.signature'])
        ->name('shopify.login');
});

Route::group(['middleware' => 'carter.web'], function () {
    Route::get('shopify/dashboard', 'NickyWoolf\Carter\Laravel\ShopifyController@dashboard')
        ->middleware(['carter.auth', 'carter.paying'])
        ->name('shopify.dashboard');
});

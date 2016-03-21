<?php

Route::group(['namespace' => 'Woolf\Carter\Http\Controller'], function ($router) {

    Route::get('signup', 'ShopifyController@registerStore')
        ->name('shopify.signup');

    Route::get('install', 'ShopifyController@install')
        ->name('shopify.install');

    Route::post('install', 'ShopifyController@install')
        ->name('shopify.action.install');

    Route::get('register', 'ShopifyController@register')
        ->name('shopify.register');

    Route::get('activate', 'ShopifyController@activate')
        ->name('shopify.activate');

    Route::get('login', 'ShopifyController@login')
        ->name('shopify.login');

    Route::get('dashboard', 'ShopifyController@dashboard')
        ->name('shopify.dashboard');

});

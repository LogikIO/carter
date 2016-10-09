<?php

Route::get(
    'shopify/signup',
    'NickyWoolf\Carter\Laravel\ShopifyController@signupForm'
)->name('shopify.signup');

Route::match(
    ['get', 'post'],
    'shopify/install',
    'NickyWoolf\Carter\Laravel\ShopifyController@install'
)->name('shopify.install');

Route::get(
    'shopify/register',
    'NickyWoolf\Carter\Laravel\ShopifyController@register'
)->name('shopify.register');

Route::get(
    'shopify/activate',
    'NickyWoolf\Carter\Laravel\ShopifyController@activate'
)->name('shopify.activate');

Route::get(
    'shopify/login',
    'NickyWoolf\Carter\Laravel\ShopifyController@login'
)->name('shopify.login');

Route::get(
    'shopify/dashboard',
    'NickyWoolf\Carter\Laravel\ShopifyController@dashboard'
)->name('shopify.dashboard');
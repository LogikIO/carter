<?php

return [

    'shopify' => [

        /*
         *  https://docs.shopify.com/api/authentication/oauth#get-the-client-redentials
         */
        'client_id'     => env('SHOPIFY_KEY'),
        'client_secret' => env('SHOPIFY_SECRET'),

        /*
         *  https://docs.shopify.com/api/authentication/oauth#scopes
         */
        'scopes'        => [
            'read_content',
            'read_themes',
            'read_products',
            'read_customers',
            'read_orders',
            'read_script_tags',
            'read_fulfillments',
            'read_shipping'
        ],

        /*
         *  https://docs.shopify.com/api/recurringapplicationcharge#create
         */
        'plan'          => [
            'name'       => 'Test Plan',
            'price'      => 0.99,
            'return_url' => env('APP_URL', 'http://localhost').'/activate',
            'trial_day'  => 0,
            'test'       => true
        ],

        'routes' => [
            'signup' => [
                'url'    => 'signup',
                'action' => '\Woolf\Carter\Http\Controllers\ShopifyController@registerStore'
            ],

            'install' => [
                'url'    => 'install',
                'action' => '\Woolf\Carter\Http\Controllers\ShopifyController@install'
            ],

            'register' => [
                'url'    => 'register',
                'action' => '\Woolf\Carter\Http\Controllers\ShopifyController@register'
            ],

            'activate' => [
                'url'    => 'activate',
                'action' => '\Woolf\Carter\Http\Controllers\ShopifyController@activate'
            ],

            'login' => [
                'url'    => 'login',
                'action' => '\Woolf\Carter\Http\Controllers\ShopifyController@login'
            ],

            'dashboard' => [
                'url'    => 'dashboard',
                'action' => '\Woolf\Carter\Http\Controllers\ShopifyController@dashboard'
            ],
        ]

    ]

];
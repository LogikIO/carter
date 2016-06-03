<?php

if (! function_exists('carter_route')) {
    function carter_route($key)
    {
        return config("carter.shopify.routes.{$key}");
    }
}

if (! function_exists('carter_auth_url')) {
    function carter_auth_url()
    {
        return app(\Woolf\Shophpify\Resource\OAuth::class)->authorizationUrl(
            config('carter.shopify.client_id'),
            implode(',', config('carter.shopify.scopes')),
            route('shopify.login'),
            session('state')
        );
    }
}
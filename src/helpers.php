<?php

if (! function_exists('carter_route')) {
    function carter_route($key)
    {
        return config("carter.shopify.routes.{$key}");
    }
}

if (! function_exists('carter_auth_url')) {
    function carter_auth_url($returnUrl)
    {
        $clientId = config('carter.shopify.client_id');
        $scopes = implode(',', config('carter.shopify.scopes'));
        $state = session('state');

        return app(\Woolf\Shophpify\Resource\OAuth::class)
            ->authorizationUrl($clientId, $scopes, $returnUrl, $state);
    }
}
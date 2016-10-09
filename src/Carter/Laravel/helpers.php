<?php

if (! function_exists('shopify_auth_url')) {
    function shopify_auth_url($returnUrl)
    {
        $clientId = config('carter.shopify.client_id');
        $scopes = implode(',', config('carter.shopify.scopes'));
        $state = session('state');

        $oauth = app(NickyWoolf\Carter\Shopify\Oauth::class);

        return $oauth->authorizationUrl($clientId, $scopes, $returnUrl, $state);
    }
}
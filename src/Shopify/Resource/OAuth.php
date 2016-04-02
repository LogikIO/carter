<?php

namespace Woolf\Carter\Shopify\Resource;

class OAuth extends Resource
{

    public function authorizeUrl($returnUrl, $state)
    {
        return $this->endpoint('admin/oauth/authorize', [
            'client_id'    => config('carter.shopify.client_id'),
            'scope'        => implode(',', config('carter.shopify.scopes')),
            'redirect_uri' => $returnUrl,
            'state'        => $state
        ]);
    }

    public function requestAccessToken($code)
    {
        $response = $this->post($this->endpoint('admin/oauth/access_token'), [
            'client_id'     => config('carter.shopify.client_id'),
            'client_secret' => config('carter.shopify.client_secret'),
            'code'          => $code,
        ]);

        return $this->parse($response, 'access_token');
    }
}
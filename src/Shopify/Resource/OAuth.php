<?php

namespace Woolf\Carter\Shopify\Resource;

class OAuth extends Resource
{
    public function authorize($clientId, $scopes, $redirectUri, $state)
    {
        $options = [
            'client_id'    => $clientId,
            'scope'        => $scopes,
            'redirect_uri' => $redirectUri,
            'state'        => $state
        ];

        return $this->redirect($this->endpoint->build('admin/oauth/authorize', $options));
    }
}
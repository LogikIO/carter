<?php

namespace Woolf\Carter\Shopify\Api;

use Illuminate\Support\Str;

class OAuth extends Api
{
    public function authorize($returnUrl)
    {
        $this->session(['state' => Str::random(40)]);

        $options = [
            'client_id'    => $this->config('client_id'),
            'redirect_uri' => $returnUrl,
            'scope'        => implode(',', $this->config('scopes')),
            'state'        => $this->session('state')
        ];

        return $this->client->redirect($this->url->build('/admin/oauth/authorize', $options));
    }
}
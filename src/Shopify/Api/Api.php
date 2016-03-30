<?php

namespace Woolf\Carter\Shopify\Api;

abstract class Api
{
    protected $client;

    protected $url;

    protected $token;

    public function __construct(Client $client, ShopUrl $url)
    {
        $this->client = $client;

        $this->url = $url;
    }

    protected function requestAccessToken()
    {
        $url = $this->url->build('/admin/oauth/access_token');

        $options = [
            'headers'     => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id'     => $this->config('client_id'),
                'client_secret' => $this->config('client_secret'),
                'code'          => $this->request('code'),
            ]
        ];

        $response = $this->client->post($url, $options);

        return $this->client->parse($response, 'access_token');
    }

    protected function tokenHeader()
    {
        return [
            'headers' => [
                'X-Shopify-Access-Token' => $this->accessToken()
            ]
        ];
    }

    protected function accessToken()
    {
        if (is_null($this->token)) {
            $this->token = $this->token() ?: $this->requestAccessToken();
        }

        return $this->token;
    }

    public function token()
    {
        return ($user = auth()->user()) ? $user->access_token : null;
    }

    protected function config($key)
    {
        return config('carter.shopify.'.$key);
    }

    protected function request($key)
    {
        return request($key);
    }

    protected function session($key)
    {
        return session($key);
    }
}
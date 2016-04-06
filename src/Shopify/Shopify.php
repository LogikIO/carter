<?php

namespace Woolf\Carter\Shopify;

use Illuminate\Support\Str;
use Woolf\Shophpify\Client;
use Woolf\Shophpify\Endpoint;

class Shopify
{

    protected $endpoint;

    protected $client;

    public function __construct(Endpoint $endpoint, Client $client)
    {
        $this->endpoint = $endpoint;

        $this->client = $client;
    }

    public function appsUrl()
    {
        return $this->endpoint->build('admin/apps');
    }

    public function authorizationUrl($redirect)
    {
        session(['state' => Str::random(40)]);

        return $this->endpoint->build('admin/oauth/authorize', [
            'client_id'    => config('carter.shopify.client_id'),
            'scope'        => implode(',', config('carter.shopify.scopes')),
            'redirect_uri' => $redirect,
            'state'        => session('state')
        ]);
    }

    public function requestAccessToken($code)
    {
        $response = $this->client->post($this->endpoint->build('admin/oauth/access_token'), [
            'client_id'     => config('carter.shopify.client_id'),
            'client_secret' => config('carter.shopify.client_secret'),
            'code'          => $code,
        ]);

        return $this->client->parse($response, 'access_token');
    }

    public function shop($fields)
    {
        if (! empty($fields)) {
            $fields = ['fields' => implode(',', $fields)];
        }

        $response = $this->client->get($this->endpoint->build('admin/shop.json', $fields));

        return $this->client->parse($response, 'shop') + ['access_token' => $this->client->getAccessToken()];
    }

    public function mapToUser()
    {
        $shop = $this->shop(['id', 'name', 'email', 'domain']);

        $shop['shopify_id'] = $shop['id'];

        unset($shop['id']);

        return $shop + ['password' => bcrypt(Str::random(20))];
    }
}
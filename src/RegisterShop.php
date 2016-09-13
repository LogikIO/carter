<?php

namespace Woolf\Carter;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Woolf\Shophpify\Client;
use Woolf\Shophpify\Resource\OAuth;
use Woolf\Shophpify\Resource\Shop;

class RegisterShop
{
    protected $oauth;

    protected $request;

    public function __construct(OAuth $oauth, Request $request)
    {
        $this->oauth = $oauth;

        $this->request = $request;
    }

    public function execute()
    {
        $user = $this->user($this->getAccessToken());

        return app('carter_user')->create($user);
    }

    protected function user($accessToken)
    {
        $shop = app(Shop::class, ['client' => new Client($accessToken)])->get(['id', 'name', 'email', 'myshopify_domain']);

        return [
            'shopify_id'   => $shop['id'],
            'name'         => $shop['name'],
            'email'        => $shop['email'],
            'domain'       => $shop['myshopify_domain'],
            'access_token' => $accessToken,
            'password'     => bcrypt(Str::random(20))
        ];
    }

    protected function getAccessToken()
    {
        return $this->oauth->requestAccessToken(
            $this->config('client_id'),
            $this->config('client_secret'),
            $this->request->code
        );
    }

    protected function config($key)
    {
        return config("carter.shopify.{$key}");
    }
}

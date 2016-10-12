<?php

namespace NickyWoolf\Carter\Laravel;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use NickyWoolf\Carter\Shopify\Api\Shop;
use NickyWoolf\Carter\Shopify\Client;
use NickyWoolf\Carter\Shopify\Oauth;

class RegisterShopifyUser
{
    protected $oauth;

    protected $request;

    public function __construct(Oauth $oauth, Request $request)
    {
        $this->oauth = $oauth;
        $this->request = $request;
    }

    public function register()
    {
        $user = $this->user($this->getAccessToken());

        return app('carter.user')->create($user);
    }

    protected function user($accessToken)
    {
        $fields = ['id', 'name', 'email', 'myshopify_domain'];

        $shop = app(Shop::class, [
            'client' => new Client($accessToken)
        ])->get(['fields' => implode(',', $fields)]);

        return [
            'shopify_id'   => $shop['id'],
            'name'         => $shop['name'],
            'email'        => $shop['email'],
            'domain'       => $shop['myshopify_domain'],
            'access_token' => $accessToken,
            'installed'    => true,
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
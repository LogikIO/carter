<?php

namespace Woolf\Carter\Shopify;

use Illuminate\Support\Str;
use Woolf\Carter\Shopify\Resource\OAuth;
use Woolf\Carter\Shopify\Resource\RecurringApplicationCharge;
use Woolf\Carter\Shopify\Resource\Shop;

class Shopify
{
    public function shop()
    {
        return $this->make(Shop::class, ['accessToken' => $this->accessToken()]);
    }

    public function oauth()
    {
        $oauth = $this->make(OAuth::class);

        session(['state' => Str::random(40)]);

        $oauth->setConfig([
            'client_id'     => config('carter.shopify.client_id'),
            'client_secret' => config('carter.shopify.client_secret'),
            'scope'         => implode(',', config('carter.shopify.scopes')),
            'redirect_uri'  => route('shopify.register'),
            'state'         => session('state'),
            'code'          => request('code')
        ]);

        return $oauth;
    }

    public function recurringCharge($id = null)
    {
        $recurringCharge = $this->make(RecurringApplicationCharge::class, ['accessToken' => $this->accessToken()]);

        $recurringCharge->setId($id);

        return $recurringCharge;
    }

    protected function accessToken()
    {
        if ($user = auth()->user()) {
            return $user->access_token;
        }

        return $this->oauth()->token();
    }

    protected function make($class, $parameters = [])
    {
        return app($class, $parameters);
    }
}
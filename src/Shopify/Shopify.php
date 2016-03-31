<?php

namespace Woolf\Carter\Shopify;

use Illuminate\Support\Str;
use Woolf\Carter\Shopify\Api\OAuth;
use Woolf\Carter\Shopify\Resource\RecurringApplicationCharge;
use Woolf\Carter\Shopify\Resource\Shop;

class Shopify
{
    public function shop()
    {
        return $this->make(Shop::class);
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
        $recurringCharge = $this->make(RecurringApplicationCharge::class);

        $recurringCharge->setId($id);

        return $recurringCharge;
    }

    protected function make($class)
    {
        return app($class);
    }
}
<?php

namespace Woolf\Carter\Shopify;

use Woolf\Carter\Shopify\Resource\OAuth;
use Woolf\Carter\Shopify\Resource\Product;
use Woolf\Carter\Shopify\Resource\RecurringApplicationCharge;
use Woolf\Carter\Shopify\Resource\Shop;

class Shopify
{

    public function resource($type)
    {
        $method = 'make'.implode('', array_map('ucfirst', explode('_', $type)));

        return $this->$method();
    }

    protected function makeProduct()
    {
        return new Product($this->domain(), $this->accessToken());
    }

    protected function makeOauth()
    {
        return new OAuth($this->domain());
    }

    protected function makeRecurringCharges()
    {
        return new RecurringApplicationCharge($this->domain(), $this->accessToken());
    }

    protected function makeShop()
    {
        return new Shop($this->domain(), $this->accessToken());
    }

    protected function domain()
    {
        return ($user = $this->user()) ? $user->domain : request('shop');
    }

    protected function accessToken()
    {
        if ($user = $this->user()) {
            return $user->access_token;
        }

        if ($code = request('code')) {
            return $this->makeOauth()->requestAccessToken(request('code'));
        }

        return null;
    }

    protected function user()
    {
        return auth()->user();
    }

}
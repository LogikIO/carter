<?php

namespace Woolf\Carter\Shopify;

use Woolf\Carter\Shopify\Api\Charge;
use Woolf\Carter\Shopify\Api\OAuth;
use Woolf\Carter\Shopify\Api\Product;
use Woolf\Carter\Shopify\Api\Shop;

class Shopify
{
    public function charge()
    {
        return $this->make(Charge::class);
    }

    public function oauth()
    {
        return $this->make(OAuth::class);
    }

    public function product()
    {
        return $this->make(Product::class);
    }

    public function shop()
    {
        return $this->make(Shop::class);
    }

    protected function make($class)
    {
        return app($class);
    }

}
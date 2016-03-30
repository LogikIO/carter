<?php

namespace Woolf\Carter;

use Illuminate\Support\Facades\Facade;
use Woolf\Carter\Shopify\Shopify;

class ShopifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Shopify::class;
    }
}
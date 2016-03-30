<?php

namespace Woolf\Carter\Shopify;

use GuzzleHttp\Client as GuzzleClient;

class Client extends GuzzleClient
{
    public static function create(array $config = [])
    {
        return new static($config);
    }
}
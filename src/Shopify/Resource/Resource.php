<?php

namespace Woolf\Carter\Shopify\Resource;

use Woolf\Shophpify\Client;
use Woolf\Shophpify\Endpoint;

abstract class Resource
{
    protected $endpoint;

    protected $client;

    public function __construct(Endpoint $endpoint, Client $client)
    {
        $this->endpoint = $endpoint;

        $this->client = $client;
    }
}
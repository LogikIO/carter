<?php

namespace Woolf\Carter\Shopify\Resource;

use Woolf\Carter\Shopify\Endpoint;

abstract class Resource
{
    protected $endpoint;

    protected $client;

    protected $accessToken;

    public function __construct(Endpoint $endpoint, $client, $accessToken = null)
    {
        $this->endpoint = $endpoint;

        $this->client = $client;

        $this->accessToken = $accessToken;
    }

    protected function client()
    {
        return call_user_func($this->client);
    }

    protected function tokenHeader()
    {
        return ['headers' => ['X-Shopify-Access-Token' => $this->accessToken]];
    }
}
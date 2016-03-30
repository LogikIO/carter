<?php

namespace Woolf\Carter\Shopify\Resource;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Woolf\Carter\Shopify\Client;
use Woolf\Carter\Shopify\Endpoint;

abstract class Resource
{
    protected $endpoint;

    protected $client;

    protected $accessToken;

    public function __construct(Endpoint $endpoint, Client $client, $accessToken = null)
    {
        $this->endpoint = $endpoint;

        $this->client = $client;

        $this->accessToken = $accessToken;
    }

    protected function redirect($url)
    {
        return new RedirectResponse($url);
    }

    protected function tokenHeader()
    {
        return ['headers' => ['X-Shopify-Access-Token' => $this->accessToken]];
    }
}
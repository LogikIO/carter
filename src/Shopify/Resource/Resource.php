<?php

namespace Woolf\Carter\Shopify\Resource;

use Psr\Http\Message\ResponseInterface;
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

    protected function client($config = [])
    {
        return $this->client->create($config);
    }

    protected function redirect($url)
    {
        return new RedirectResponse($url);
    }

    protected function tokenHeader()
    {
        return ['headers' => ['X-Shopify-Access-Token' => $this->accessToken]];
    }

    public function parse(ResponseInterface $response, $extract = false)
    {
        $response = json_decode($response->getBody(), true);

        if ($extract) {
            return (isset($response[$extract])) ? $response[$extract] : false;
        }

        return $response;
    }
}
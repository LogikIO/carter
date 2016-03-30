<?php

namespace Woolf\Carter\Shopify;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Client
{

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }

    public function get($url, array $options = [])
    {
        return $this->makeRequest('get', $url, $options);
    }

    public function post($url, array $options = [])
    {
        return $this->makeRequest('post', $url, $options);
    }

    protected function makeRequest($method, $url, array $options = [])
    {
        return $this->client()->$method($url, $options);
    }

    public function parse(ResponseInterface $response, $extract = false)
    {
        $response = json_decode($response->getBody(), true);

        if ($extract) {
            return (isset($response[$extract])) ? $response[$extract] : false;
        }

        return $response;
    }

    protected function client()
    {
        return new GuzzleClient();
    }
}
<?php

namespace Woolf\Carter\Shopify\Resource;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class Resource
{
    protected $domain;

    protected $accessToken;

    public function __construct($domain, $accessToken = null)
    {
        $this->domain = $domain;

        $this->accessToken = $accessToken;
    }

    public function endpoint($path, $query = false)
    {
        $url = 'https://'.$this->domain.'/'.trim($path, '/');

        if ($query) {
            $url .= '?'.urldecode(http_build_query($query, '', '&'));
        }

        return $url;
    }

    public function get($url, array $options = [])
    {
        return $this->client()->get($url, $options);
    }

    public function post($url, array $options = [])
    {
        return $this->client()->post($url, $this->optionsToFormParams($options));
    }

    public function put($url, array $options = [])
    {
        return $this->client()->put($url, $this->optionsToFormParams($options));
    }

    public function delete($url, array $options = [])
    {
        return $this->client()->delete($url, $this->optionsToFormParams($options));
    }

    protected function optionsToFormParams(array $options = [])
    {
        return (! empty($options)) ? ['form_params' => $options] : $options;
    }

    protected function client()
    {
        $config = (! is_null($this->accessToken)) ? $this->tokenHeader() : [];

        return new Client($config);
    }

    protected function tokenHeader()
    {
        return ['headers' => ['X-Shopify-Access-Token' => $this->accessToken]];
    }

    public function parse(ResponseInterface $response, $extract = false)
    {
        $response = json_decode($response->getBody(), true);

        if ($extract) {
            return isset($response[$extract]) ? $response[$extract] : false;
        }

        return $response;
    }
}
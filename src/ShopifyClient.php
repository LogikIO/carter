<?php

namespace Woolf\Carter;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ShopifyClient
{

    protected $domain;

    public function domain($domain)
    {
        $this->domain = $domain;
    }

    public function get($url, array $options = [])
    {
        return $this->client()->get($url, $options);
    }

    public function post($url, array $options = [])
    {
        return $this->client()->post($url, $options);
    }

    public function endpoint($path, array $query = [])
    {
        $url = 'https://'.$this->domain.$path;

        if (! empty($query)) {
            $url .= '?'.http_build_query($query, '', '&');
        }

        return $url;
    }

    protected function client()
    {
        return new Client();
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }
}

<?php

namespace Woolf\Carter\Shopify;

class ShopUrl
{
    protected $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function build($path, array $query = [])
    {
        $url = 'https://'.$this->domain.$path;

        if (! empty($query)) {
            $url .= '?'.http_build_query($query, '', '&');
        }

        return $url;
    }
}
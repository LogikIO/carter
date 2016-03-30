<?php

namespace Woolf\Carter\Shopify\Api;

class Product extends Api
{
    public function all()
    {
        $url = $this->url->build("/admin/products.json");

        $response = $this->client->get($url, $this->tokenHeader());

        return $this->client->parse($response, 'products');
    }
}
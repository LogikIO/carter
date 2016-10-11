<?php

namespace NickyWoolf\Carter\Shopify\Api;

class Shop extends Resource
{
    public function get($query = false)
    {
        return $this->client->get([
            'path'    => 'shop.json',
            'query'   => $query,
            'extract' => 'shop',
        ]);
    }
}
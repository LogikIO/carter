<?php

namespace NickyWoolf\Carter\Shopify\Api;

use NickyWoolf\Carter\Shopify\Resource;

class Shop extends Resource
{
    public function get($fields = [])
    {
        $query = ! empty($fields) ? ['fields' => implode(',', $fields)] : false;

        return $this->httpGet([
            'path'    => 'shop.json',
            'query'   => $query,
            'extract' => 'shop',
        ]);
    }
}
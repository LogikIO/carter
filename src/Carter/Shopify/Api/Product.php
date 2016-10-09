<?php

namespace NickyWoolf\Carter\Shopify\Api;

use NickyWoolf\Carter\Shopify\Resource;

class Product extends Resource
{
    public function all()
    {
        return $this->httpGet([
            'path'    => 'products.json',
            'extract' => 'products',
        ]);
    }

    public function get($id)
    {
        return $this->httpGet([
            'path'    => "products/{$id}.json",
            'extract' => 'product',
        ]);
    }

    public function count($query = [])
    {
        return $this->httpGet([
            'path'    => 'products/count.json',
            'query'   => $query,
            'extract' => 'count',
        ]);
    }

    public function create(array $product)
    {
        return $this->httpPost([
            'path'    => 'products.json',
            'options' => $product,
            'extract' => 'product',
        ]);
    }

    public function update($id, array $product)
    {
        return $this->httpPut([
            'path'    => "products/{$id}.json",
            'options' => $product,
            'extract' => 'product',
        ]);
    }

    public function delete($id)
    {
        return $this->httpDelete([
            'path' => "products/{$id}.json"
        ])->getStatusCode();
    }
}
<?php

namespace NickyWoolf\Carter\Shopify\Api;

class Product extends Resource
{
    public function all($query = false)
    {
        return $this->client->get([
            'path'    => 'products.json',
            'query'   => $query,
            'extract' => 'products',
        ]);
    }

    public function get($id, $query = false)
    {
        return $this->client->get([
            'path'    => "products/{$id}.json",
            'query'   => $query,
            'extract' => 'product',
        ]);
    }

    public function count($query = [])
    {
        return $this->client->get([
            'path'    => 'products/count.json',
            'query'   => $query,
            'extract' => 'count',
        ]);
    }

    public function create(array $product)
    {
        return $this->client->post([
            'path'    => 'products.json',
            'options' => ['product' => $product],
            'extract' => 'product',
        ]);
    }

    public function update($id, array $product)
    {
        return $this->client->put([
            'path'    => "products/{$id}.json",
            'options' => ['product' => $product],
            'extract' => 'product',
        ]);
    }

    public function delete($id)
    {
        return $this->client->delete([
            'path' => "products/{$id}.json"
        ])->getStatusCode();
    }
}
<?php

namespace Woolf\Carter\Shopify\Resource;

class Product extends Resource
{

    public function all()
    {
        $response = $this->client->get($this->endpoint->build('admin/products.json'));

        return $this->client->parse($response, 'products');
    }

    public function get($id)
    {
        $response = $this->client->get($this->endpoint->build("admin/products/{$id}.json"));

        return $this->client->parse($response, 'product');
    }

    public function count($query = [])
    {
        $response = $this->client->get($this->endpoint->build('admin/products/count.json', $query));

        return $this->client->parse($response, 'count');
    }

    public function create(array $product)
    {
        $response = $this->client->post($this->endpoint->build('admin/products.json'), compact('product'));

        return $this->client->parse($response, 'product');
    }

    public function update($id, array $product)
    {
        $response = $this->client->put($this->endpoint->build("admin/products/{$id}.json"), compact('product'));

        return $this->client->parse($response, 'product');
    }

    public function delete($id)
    {
        $response = $this->client->delete($this->endpoint->build("admin/products/{$id}.json"));

        return $response->getStatusCode();
    }

}
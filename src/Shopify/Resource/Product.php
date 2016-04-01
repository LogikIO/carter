<?php

namespace Woolf\Carter\Shopify\Resource;

class Product extends Resource
{
    protected $id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function count($query = [])
    {
        $url = $this->endpoint->build('admin/products/count.json', $query);

        $response = $this->client->create()->get($url, $this->tokenHeader());

        return $this->parse($response, 'count');
    }

    public function get()
    {
        return (is_null($this->id)) ? $this->all() : $this->single();
    }

    protected function all()
    {
        $url = $this->endpoint->build('admin/products.json');

        $response = $this->client->create()->get($url, $this->tokenHeader());

        return $this->parse($response, 'products');
    }

    protected function single()
    {
        $url = $this->endpoint->build("admin/products/{$this->id}.json");

        $response = $this->client->create()->get($url, $this->tokenHeader());

        return $this->parse($response, 'product');
    }

    public function create(array $product)
    {
        $url = $this->endpoint->build("admin/products.json");

        $response = $this->client()->post($url, ['form_params' => ['product' => $product]] + $this->tokenHeader());

        return $this->parse($response, 'product');
    }

    public function update(array $product)
    {
        $url = $this->endpoint->build("admin/products/{$this->id}.json");

        $response = $this->client()->put($url, ['form_params' => ['product' => $product]] + $this->tokenHeader());

        return $this->parse($response, 'product');
    }

    public function delete()
    {
        $url = $this->endpoint->build("admin/products/{$this->id}.json");

        $response =  $this->client()->delete($url, $this->tokenHeader());

        return $response->getStatusCode();
    }
}
<?php

namespace Woolf\Carter\Shopify\Resource;

class Product extends Resource
{
    protected $id;

    public function setId($id)
    {
        $this->id = $id;
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
}
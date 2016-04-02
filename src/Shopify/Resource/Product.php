<?php

namespace Woolf\Carter\Shopify\Resource;

class Product extends ResourceWithId
{

    public function retrieve()
    {
        $path = 'admin/products';

        $url = $this->endpoint($this->haveId() ? "{$path}/{$this->id}.json" : "{$path}.json");

        return $this->parse(
            $this->get($url, $this->tokenHeader()),
            $this->haveId() ? 'product' : 'products'
        );
    }

    public function count($query = [])
    {
        $response = $this->get($this->endpoint('admin/products/count.json', $query));

        return $this->parse($response, 'count');
    }

    public function create(array $product)
    {
        $url = $this->endpoint("admin/products.json");

        $response = $this->post($url, ['product' => $product]);

        return $this->parse($response, 'product');
    }

    public function update(array $product)
    {
        $response = $this->put($this->endpoint("admin/products/{$this->id}.json"), ['product' => $product]);

        return $this->parse($response, 'product');
    }

    public function destroy()
    {
        $response = $this->delete($this->endpoint("admin/products/{$this->id}.json"));

        return $response->getStatusCode();
    }

}
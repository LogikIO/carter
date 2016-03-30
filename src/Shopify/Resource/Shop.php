<?php

namespace Woolf\Carter\Shopify\Resource;

use Woolf\Carter\Shopify\Endpoint;

class Shop
{
    protected $endpoint;

    protected $client;

    public function __construct(Endpoint $endpoint, $client)
    {
        $this->endpoint = $endpoint;

        $this->client = $client;
    }

    public function get(array $fields = [])
    {
        if (! empty($fields)) {
            $fields = ['fields' => implode(',', $fields)];
        }
        $url = $this->endpoint->build('admin/shop.json', $fields);

        return $this->client()->get($url);
    }

    protected function client()
    {
        return call_user_func($this->client);
    }
}
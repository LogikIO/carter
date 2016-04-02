<?php

namespace Woolf\Carter\Shopify\Resource;

use Illuminate\Support\Str;

class Shop extends Resource
{

    public function mapToUser()
    {
        $shop = $this->retrieve(['id', 'name', 'email', 'domain']);

        $shop['shopify_id'] = $shop['id'];

        unset($shop['id']);

        $shop['password'] = bcrypt(Str::random(20));

        return $shop;
    }

    public function retrieve(array $fields = [])
    {
        if (! empty($fields)) {
            $fields = ['fields' => implode(',', $fields)];
        }

        $response = $this->get($this->endpoint('admin/shop.json', $fields));

        return $this->parse($response, 'shop') + ['access_token' => $this->accessToken];
    }
}
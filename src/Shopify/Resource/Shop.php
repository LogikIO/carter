<?php

namespace Woolf\Carter\Shopify\Resource;

class Shop extends Resource
{

    public function apps()
    {
        return $this->redirect($this->endpoint->build('admin/apps'));
    }

    public function get(array $fields = [])
    {
        if (! empty($fields)) {
            $fields = ['fields' => implode(',', $fields)];
        }

        $url = $this->endpoint->build('admin/shop.json', $fields);

        return $this->client->create()->get($url, $this->tokenHeader());
    }
}
<?php

namespace NickyWoolf\Carter\Shopify\Api;

class Checkout extends Resource
{
    public function all()
    {
        return $this->client->get([
            'path'    => 'checkouts.json',
            'extract' => 'checkouts',
        ]);
    }

    public function count()
    {
        return $this->client->get([
            'path'    => 'checkouts/count.json',
            'extract' => 'count',
        ]);
    }
}
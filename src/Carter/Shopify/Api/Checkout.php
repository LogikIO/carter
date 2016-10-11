<?php

namespace NickyWoolf\Carter\Shopify\Api;

use NickyWoolf\Carter\Shopify\Resource;

class Checkout extends Resource
{
    public function all()
    {
        return $this->httpGet([
            'path'    => 'checkouts.json',
            'extract' => 'checkouts',
        ]);
    }

    public function count()
    {
        return $this->httpGet([
            'path'    => 'checkouts/count.json',
            'extract' => 'count',
        ]);
    }
}
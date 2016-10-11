<?php

namespace NickyWoolf\Carter\Shopify\Api;

use NickyWoolf\Carter\Shopify\Resource;

class Webhook extends Resource
{
    public function all($query = false)
    {
        return $this->httpGet([
            'path'    => 'webhooks.json',
            'query'   => $query,
            'extract' => 'webhooks',
        ]);
    }

    public function get($id, $query = false)
    {
        return $this->httpGet([
            'path'    => "webhooks/{$id}.json",
            'query'   => $query,
            'extract' => 'webhook',
        ]);
    }

    public function count($query = false)
    {
        return $this->httpGet([
            'path'    => 'webhooks/count.json',
            'query'   => $query,
            'extract' => 'count',
        ]);
    }

    public function create(array $webhook)
    {
        return $this->httpPost([
            'path'    => 'webhooks.json',
            'options' => ['webhook' => $webhook],
            'extract' => 'webhook',
        ]);
    }

    public function update($id, array $webhook)
    {
        return $this->httpPut([
            'path'    => "webhooks/{$id}.json",
            'options' => ['webhook' => $webhook],
            'extract' => 'webhook',
        ]);
    }

    public function delete($id)
    {
        return $this->httpDelete([
            'path' => "products/{$id}.json"
        ])->getStatusCode();
    }
}
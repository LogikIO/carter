<?php

namespace NickyWoolf\Carter\Shopify\Api;

class Webhook extends Resource
{
    public function all($query = false)
    {
        return $this->client->get([
            'path'    => 'webhooks.json',
            'query'   => $query,
            'extract' => 'webhooks',
        ]);
    }

    public function get($id, $query = false)
    {
        return $this->client->get([
            'path'    => "webhooks/{$id}.json",
            'query'   => $query,
            'extract' => 'webhook',
        ]);
    }

    public function count($query = false)
    {
        return $this->client->get([
            'path'    => 'webhooks/count.json',
            'query'   => $query,
            'extract' => 'count',
        ]);
    }

    public function create(array $webhook)
    {
        return $this->client->post([
            'path'    => 'webhooks.json',
            'options' => ['webhook' => $webhook],
            'extract' => 'webhook',
        ]);
    }

    public function update($id, array $webhook)
    {
        return $this->client->post([
            'path'    => "webhooks/{$id}.json",
            'options' => ['webhook' => $webhook],
            'extract' => 'webhook',
        ]);
    }

    public function delete($id)
    {
        return $this->client->delete([
            'path' => "products/{$id}.json"
        ])->getStatusCode();
    }
}
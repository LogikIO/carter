<?php

namespace NickyWoolf\Carter\Shopify\Api;

class ApplicationCharge extends Resource
{
    public function all($query = false)
    {
        return $this->client->get([
            'path'    => 'application_charges.json',
            'query'   => $query,
            'extract' => 'application_charges',
        ]);
    }

    public function get($id, $query = false)
    {
        return $this->client->get([
            'path'    => "application_charges/{$id}.json",
            'query'   => $query,
            'extract' => 'application_charge',
        ]);
    }

    public function create($charge)
    {
        return $this->client->post([
            'path'    => 'application_charges.json',
            'options' => ['application_charge' => $charge],
            'extract' => 'application_charge',
        ]);
    }

    public function activate($id)
    {
        return $this->client->post([
            'path'    => "application_charges/{$id}/activate.json",
            'options' => ['application_charge' => $id]
        ])->getStatusCode();
    }
}
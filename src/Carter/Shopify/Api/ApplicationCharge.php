<?php

namespace NickyWoolf\Carter\Shopify\Api;

use NickyWoolf\Carter\Shopify\Resource;

class ApplicationCharge extends Resource
{
    public function all()
    {
        return $this->httpGet([
            'path'    => 'application_charges.json',
            'extract' => 'application_charges',
        ]);
    }

    public function get($id)
    {
        return $this->httpGet([
            'path'    => "application_charges/{$id}.json",
            'extract' => 'application_charge',
        ]);
    }

    public function create($charge)
    {
        return $this->httpPost([
            'path'    => 'application_charges.json',
            'options' => ['application_charge' => $charge],
            'extract' => 'application_charge',
        ]);
    }

    public function activate($id)
    {
        return $this->httpPost([
            'path'    => "application_charges/{$id}/activate.json",
            'options' => ['application_charge' => $id]
        ])->getStatusCode();
    }
}
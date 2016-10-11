<?php

namespace NickyWoolf\Carter\Shopify\Api;

class RecurringApplicationCharge extends Resource
{
    public function all($query = false)
    {
        return $this->client->get([
            'path'    => 'recurring_application_charges.json',
            'query'   => $query,
            'extract' => 'recurring_application_charges',
        ]);
    }

    public function get($id, $query = false)
    {
        return $this->client->get([
            'path'    => "recurring_application_charges/{$id}.json",
            'query'   => $query,
            'extract' => 'recurring_application_charge',
        ]);
    }

    public function activate($id)
    {
        return $this->client->post([
            'path'    => "recurring_application_charges/{$id}/activate.json",
            'options' => ['recurring_application_charge' => $id],
            'extract' => 'recurring_application_charge',
        ]);
    }

    public function create($plan)
    {
        return $this->client->post([
            'path'    => 'recurring_application_charges.json',
            'options' => ['recurring_application_charge' => $plan],
            'extract' => 'recurring_application_charge',
        ]);
    }

    public function isAccepted($id)
    {
        $charge = $this->get($id);

        return in_array($charge['status'], ['accepted', 'active']);
    }
}
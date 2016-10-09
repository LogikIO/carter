<?php

namespace NickyWoolf\Carter\Shopify\Api;

use NickyWoolf\Carter\Shopify\Resource;

class RecurringApplicationCharge extends Resource
{
    public function all()
    {
        return $this->httpGet([
            'path'    => 'recurring_application_charges.json',
            'extract' => 'recurring_application_charges',
        ]);
    }

    public function get($id)
    {
        return $this->httpGet([
            'path'    => "recurring_application_charges/{$id}.json",
            'extract' => 'recurring_application_charge',
        ]);
    }

    public function activate($id)
    {
        return $this->httpPost([
            'path'    => "recurring_application_charges/{$id}/activate.json",
            'options' => ['recurring_application_charge' => $id],
            'extract' => 'recurring_application_charge',
        ]);
    }

    public function create($plan)
    {
        return $this->httpPost([
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
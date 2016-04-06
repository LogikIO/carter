<?php

namespace Woolf\Carter\Shopify\Resource;

class RecurringApplicationCharge extends Resource
{

    public function all()
    {
        $response = $this->client->get($this->endpoint->build("admin/recurring_application_charges.json"));

        return $this->client->parse($response, 'recurring_application_charges');
    }

    public function get($id)
    {
        $response = $this->client->get($this->endpoint->build("admin/recurring_application_charges/{$id}.json"));

        return $this->client->parse($response, 'recurring_application_charge');
    }

    public function activate($id)
    {
        $url = $this->endpoint->build("admin/recurring_application_charges/{$id}/activate.json");

        $response = $this->client->post($url, ['recurring_application_charge' => $id]);

        return $this->client->parse($response, 'recurring_application_charge');
    }

    public function create($plan)
    {
        $url = $this->endpoint->build('admin/recurring_application_charges.json');

        $response = $this->client->post($url, ['recurring_application_charge' => $plan]);

        return $this->client->parse($response, 'recurring_application_charge');
    }

    public function isAccepted($id)
    {
        $charge = $this->get($id);

        return in_array($charge['status'], ['accepted', 'active']);
    }
}
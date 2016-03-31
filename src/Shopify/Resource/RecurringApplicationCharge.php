<?php

namespace Woolf\Carter\Shopify\Resource;

use InvalidArgumentException;

class RecurringApplicationCharge extends Resource
{
    protected $id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function activate()
    {
        if (is_null($this->id)) {
            throw new InvalidArgumentException('Charge Id Missing');
        }

        $options = ['form_params' => ['recurring_application_charge' => $this->id]];

        $url = $this->endpoint->build("admin/recurring_application_charges/{$this->id}/activate.json");

        return $this->client->create()->post($url, $options + $this->tokenHeader());
    }

    public function confirm($charge)
    {
        return $this->redirect($charge['confirmation_url']);
    }

    public function create($plan)
    {
        $options = ['form_params' => ['recurring_application_charge' => $plan]];

        $url = $this->endpoint->build('admin/recurring_application_charges.json');

        return $this->client->create()->post($url, $options + $this->tokenHeader());
    }

    public function isAccepted()
    {
        if (is_null($this->id)) {
            throw new InvalidArgumentException('Charge Id Missing');
        }

        $charge = $this->parse($this->get(), 'recurring_application_charge');

        $acceptable = ['accepted', 'active'];

        return in_array($charge['status'], $acceptable);
    }

    public function get()
    {
        $url = (! is_null($this->id))
            ? $this->endpoint->build("admin/recurring_application_charges/{$this->id}.json")
            : $this->endpoint->build("admin/recurring_application_charges.json");

        return $this->client->create()->get($url, $this->tokenHeader());
    }
}
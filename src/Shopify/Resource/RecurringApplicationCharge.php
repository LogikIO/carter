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

        $response = $this->client->create()->post($url, $options + $this->tokenHeader());

        return $this->parse($response, 'recurring_application_charge');
    }

    public function isAccepted()
    {
        if (is_null($this->id)) {
            throw new InvalidArgumentException('Charge Id Missing');
        }

        $charge = $this->get();

        return in_array($charge['status'], ['accepted', 'active']);
    }

    public function get()
    {
        return (is_null($this->id)) ? $this->all() : $this->single();
    }

    protected function all()
    {
        $url = $this->endpoint->build('admin/recurring_application_charges.json');

        $response = $this->client->create()->get($url, $this->tokenHeader());

        return $this->parse($response, 'recurring_application_charges');
    }

    protected function single()
    {
        $url = $this->endpoint->build("admin/recurring_application_charges/{$this->id}.json");

        $response = $this->client->create()->get($url, $this->tokenHeader());

        return $this->parse($response, 'recurring_application_charge');
    }
}
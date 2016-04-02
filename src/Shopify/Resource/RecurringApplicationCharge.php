<?php

namespace Woolf\Carter\Shopify\Resource;

class RecurringApplicationCharge extends ResourceWithId
{

    public function activate()
    {
        $this->mustIncludeId();

        $response = $this->post(
            $this->endpoint("admin/recurring_application_charges/{$this->id}/activate.json"),
            ['recurring_application_charge' => $this->id]
        );

        return $this->parse($response, 'recurring_application_charge');
    }

    public function create($plan)
    {
        $response = $this->post(
            $this->endpoint('admin/recurring_application_charges.json'),
            ['recurring_application_charge' => $plan]
        );

        return $this->parse($response, 'recurring_application_charge');
    }

    public function isAccepted()
    {
        $this->mustIncludeId();

        $charge = $this->retrieve();

        return in_array($charge['status'], ['accepted', 'active']);
    }

    public function retrieve()
    {
        $path = 'admin/recurring_application_charges';

        $url = $this->endpoint($this->haveId() ? "{$path}/{$this->id}.json" : "{$path}.json");

        return $this->parse(
            $this->get($url, $this->tokenHeader()),
            $this->haveId() ? 'recurring_application_charge' : 'recurring_application_charges'
        );
    }
}
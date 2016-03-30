<?php

namespace Woolf\Carter\Shopify\Api;

class Charge extends Api
{
    public function activate($id)
    {
        $url = $this->url->build("/admin/recurring_application_charges/{$id}/activate.json");

        $options = [
            'form_params' => [
                'recurring_application_charge' => $id
            ]
        ];

        return $this->client->post($url, $options + $this->tokenHeader())->getStatusCode();
    }


    public function getRecurring($id)
    {
        $url = $this->url->build("/admin/recurring_application_charges/{$id}.json");

        $response = $this->client->get($url, $this->tokenHeader());

        return $this->client->parse($response, 'recurring_application_charge');
    }

    public function recurring()
    {
        $url = $this->url->build('/admin/recurring_application_charges.json');

        $options = [
            'form_params' => [
                'recurring_application_charge' => $this->config('plan')
            ]
        ];

        $response = $this->client->post($url, $options + $this->tokenHeader());

        $charge = $this->client->parse($response, 'recurring_application_charge');

        return $this->client->redirect($charge['confirmation_url']);
    }
}
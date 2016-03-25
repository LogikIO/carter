<?php

namespace Woolf\Carter;

use Illuminate\Support\Str;

class ShopifyGateway
{

    protected $client;

    protected $token;

    public function __construct(ShopifyClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param $chargeId
     * @return int
     */
    public function activate($chargeId)
    {
        $parameters = ['form_params' => ['recurring_application_charge' => $chargeId]] + $this->tokenHeader();

        $response = $this->client->post(
            $this->client->endpoint("/admin/recurring_application_charges/{$chargeId}/activate.json"), $parameters
        );

        return $response->getStatusCode();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apps()
    {
        return $this->client->redirect($this->client->endpoint('/admin/apps'));
    }

    /**
     * @param $returnUrl
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function authorize($returnUrl)
    {
        $this->client->setState(Str::random(40));

        $endpoint = $this->client->endpoint('/admin/oauth/authorize', [
            'client_id'    => $this->client->clientId(),
            'redirect_uri' => $returnUrl,
            'scope'        => implode(',', $this->client->scopes()),
            'state'        => $this->client->getState()
        ]);

        return $this->client->redirect($endpoint);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function charge()
    {
        $parameters = ['form_params' => ['recurring_application_charge' => $this->client->plan()]] + $this->tokenHeader();

        $response = $this->client->post(
            $this->client->endpoint('/admin/recurring_application_charges.json'), $parameters
        );

        $charge = $this->parseResponse($response->getBody(), 'recurring_application_charge');

        return $this->client->redirect($charge['confirmation_url']);
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function getCharge($id)
    {
        $response = $this->client->get(
            $this->client->endpoint("/admin/recurring_application_charges/{$id}.json"), $this->tokenHeader()
        );

        return $this->parseResponse($response->getBody(), 'recurring_application_charge');
    }

    /**
     * @return bool|mixed
     */
    public function requestAccessToken()
    {
        $response = $this->client->post($this->client->endpoint('/admin/oauth/access_token'), [
            'headers'     => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id'     => $this->client->clientId(),
                'client_secret' => $this->client->clientSecret(),
                'code'          => $this->client->code(),
            ]
        ]);

        return $this->parseResponse($response->getBody(), 'access_token');
    }

    /**
     * @return bool|mixed
     */
    public function store()
    {
        $response = $this->client->get($this->client->endpoint('/admin/shop.json'), $this->tokenHeader());

        $store = $this->parseResponse($response->getBody(), 'shop');

        return  $store + ['access_token' => $this->accessToken()];
    }

    protected function accessToken()
    {
        if (is_null($this->token)) {
            $this->token = $this->client->token() ?: $this->requestAccessToken();
        }

        return $this->token;
    }

    protected function parseResponse($body, $return = false)
    {
        $response = json_decode($body, true);

        if ($return) {
            return (isset($response[$return])) ? $response[$return] : false;
        }

        return $response;
    }

    protected function tokenHeader()
    {
        return ['headers' => ['X-Shopify-Access-Token' => $this->accessToken()]];
    }
}
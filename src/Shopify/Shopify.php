<?php

namespace Woolf\Carter\Shopify;

use Illuminate\Support\Str;

class Shopify
{
    protected $client;

    protected $url;

    protected $token;

    public function __construct(Client $client, ShopUrl $url)
    {
        $this->client = $client;

        $this->url = $url;
    }

    public function activate($chargeId)
    {
        $url = $this->url->build("/admin/recurring_application_charges/{$chargeId}/activate.json");

        $options = [
            'form_params' => [
                'recurring_application_charge' => $chargeId
            ]
        ];

        return $this->client->post($url, $options + $this->tokenHeader())->getStatusCode();
    }

    public function authorize($returnUrl)
    {
        $this->session(['state' => Str::random(40)]);

        $options = [
            'client_id'    => $this->config('client_id'),
            'redirect_uri' => $returnUrl,
            'scope'        => implode(',', $this->config('scopes')),
            'state'        => $this->session('state')
        ];

        return $this->client->redirect($this->url->build('/admin/oauth/authorize', $options));
    }

    public function charge()
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

    public function getCharge($id)
    {
        $url = $this->url->build("/admin/recurring_application_charges/{$id}.json");

        $response = $this->client->get($url, $this->tokenHeader());

        return $this->client->parse($response, 'recurring_application_charge');
    }

    public function products()
    {
        $url = $this->url->build("/admin/products.json");

        $response = $this->client->get($url, $this->tokenHeader());

        return $this->client->parse($response, 'products');
    }

    public function requestAccessToken()
    {
        $url = $this->url->build('/admin/oauth/access_token');

        $options = [
            'headers'     => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id'     => $this->config('client_id'),
                'client_secret' => $this->config('client_secret'),
                'code'          => $this->request('code'),
            ]
        ];

        $response = $this->client->post($url, $options);

        return $this->client->parse($response, 'access_token');
    }

    public function shop()
    {
        $url = $this->url->build('/admin/shop.json');

        $response = $this->client->get($url, $this->tokenHeader());

        return  $this->client->parse($response, 'shop') + ['access_token' => $this->accessToken()];
    }

    public function shopApps()
    {
        return $this->client->redirect($this->url->build('/admin/apps'));
    }

    protected function tokenHeader()
    {
        return [
            'headers' => [
                'X-Shopify-Access-Token' => $this->accessToken()
            ]
        ];
    }

    protected function accessToken()
    {
        if (is_null($this->token)) {
            $this->token = $this->token() ?: $this->requestAccessToken();
        }

        return $this->token;
    }

    public function token()
    {
        return ($user = auth()->user()) ? $user->access_token : null;
    }

    protected function config($key)
    {
        return config('carter.shopify.'.$key);
    }

    protected function request($key)
    {
        return request($key);
    }

    protected function session($key)
    {
        return session($key);
    }
}
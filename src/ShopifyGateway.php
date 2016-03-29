<?php

namespace Woolf\Carter;

use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ShopifyGateway
{

    protected $app;

    protected $token;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    protected function client()
    {
        return new Client();
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }

    public function getState()
    {
        return $this->request()->session()->get('state');
    }

    public function setState($value)
    {
        $this->request()->session()->set('state', $value);
    }

    protected function config($key)
    {
        return $this->app['config']->get('carter.shopify.'.$key);
    }

    protected function request()
    {
        return $this->app['request'];
    }

    public function endpoint($path, array $query = [])
    {
        $url = 'https://'.$this->domain().$path;

        if (! empty($query)) {
            $url .= '?'.http_build_query($query, '', '&');
        }

        return $url;
    }

    public function get($url, array $options = [])
    {
        return $this->client()->get($url, $options);
    }

    public function post($url, array $options = [])
    {
        return $this->client()->post($url, $options);
    }

    public function clientId()
    {
        return $this->config('client_id');
    }

    public function clientSecret()
    {
        return $this->config('client_secret');
    }

    public function code()
    {
        return $this->request()->input('code');
    }

    public function domain()
    {
        return ($user = $this->app['auth']->user()) ? $user->domain : $this->request()->input('shop');
    }

    public function plan()
    {
        return $this->config('plan');
    }

    public function scopes()
    {
        return $this->config('scopes');
    }

    public function token()
    {
        return ($user = $this->app['auth']->user()) ? $user->access_token : null;
    }

    /**
     * @param $chargeId
     * @return int
     */
    public function activate($chargeId)
    {
        $parameters = ['form_params' => ['recurring_application_charge' => $chargeId]] + $this->tokenHeader();

        $response = $this->post(
            $this->endpoint("/admin/recurring_application_charges/{$chargeId}/activate.json"), $parameters
        );

        return $response->getStatusCode();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apps()
    {
        return $this->redirect($this->endpoint('/admin/apps'));
    }

    /**
     * @param $returnUrl
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function authorize($returnUrl)
    {
        $this->setState(Str::random(40));

        $endpoint = $this->endpoint('/admin/oauth/authorize', [
            'client_id'    => $this->clientId(),
            'redirect_uri' => $returnUrl,
            'scope'        => implode(',', $this->scopes()),
            'state'        => $this->getState()
        ]);

        return $this->redirect($endpoint);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function charge()
    {
        $parameters = ['form_params' => ['recurring_application_charge' => $this->plan()]] + $this->tokenHeader();

        $response = $this->post(
            $this->endpoint('/admin/recurring_application_charges.json'), $parameters
        );

        $charge = $this->parseResponse($response->getBody(), 'recurring_application_charge');

        return $this->redirect($charge['confirmation_url']);
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function getCharge($id)
    {
        $response = $this->get(
            $this->endpoint("/admin/recurring_application_charges/{$id}.json"), $this->tokenHeader()
        );

        return $this->parseResponse($response->getBody(), 'recurring_application_charge');
    }

    /**
     * @return bool|mixed
     */
    public function requestAccessToken()
    {
        $response = $this->post($this->endpoint('/admin/oauth/access_token'), [
            'headers'     => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id'     => $this->clientId(),
                'client_secret' => $this->clientSecret(),
                'code'          => $this->code(),
            ]
        ]);

        return $this->parseResponse($response->getBody(), 'access_token');
    }

    /**
     * @return bool|mixed
     */
    public function store()
    {
        $response = $this->get($this->endpoint('/admin/shop.json'), $this->tokenHeader());

        $store = $this->parseResponse($response->getBody(), 'shop');

        return  $store + ['access_token' => $this->accessToken()];
    }

    protected function accessToken()
    {
        if (is_null($this->token)) {
            $this->token = $this->token() ?: $this->requestAccessToken();
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
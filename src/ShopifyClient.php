<?php

namespace Woolf\Carter;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Contracts\Foundation\Application;

class ShopifyClient
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
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

    protected function client()
    {
        return new Client();
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }
}

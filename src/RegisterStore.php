<?php

namespace Woolf\Carter;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use Woolf\Carter\Shopify\Shopify;

class RegisterStore
{
    CONST HTTP_OK = 200;

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $shop = $this->shopify()->shop();

        $user = $this->user()->create([
            'name'         => $shop['name'],
            'email'        => $shop['email'],
            'password'     => bcrypt(Str::random(10)),
            'domain'       => $shop['domain'],
            'shopify_id'   => $shop['id'],
            'access_token' => $shop['access_token']
        ]);

        $this->login($user);

        return $this;
    }

    protected function shopify()
    {
        return $this->app->make(Shopify::class);
    }

    protected function user()
    {
        return $this->auth()->user() ?: $this->app->make('carter.auth.model');
    }

    protected function auth()
    {
        return $this->app['auth'];
    }

    public function login($user)
    {
        return $this->auth()->login($user);
    }

    public function charge()
    {
        return $this->shopify()->charge();
    }

    public function activate($chargeId)
    {
        if ($this->shopify()->activate($chargeId) !== static::HTTP_OK) {
            throw new \Exception();
        }

        $this->user()->update(['charge_id' => $chargeId]);
    }

    public function hasAcceptedCharge($chargeId)
    {
        $charge = $this->shopify()->getCharge($chargeId);

        $acceptable = ['accepted', 'active'];

        return in_array($charge['status'], $acceptable);
    }

}
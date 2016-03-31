<?php

namespace Woolf\Carter;

use Illuminate\Support\Str;
use Shopify;

class RegisterShop
{
    CONST HTTP_OK = 200;

    public function register()
    {
        $shop = Shopify::shop()->get();

        $user = $this->user()->create([
            'name'         => $shop['name'],
            'email'        => $shop['email'],
            'password'     => bcrypt(Str::random(10)),
            'domain'       => $shop['domain'],
            'shopify_id'   => $shop['id'],
            'access_token' => $shop['access_token']
        ]);

        auth()->login($user);

        return $this;
    }

    protected function user()
    {
        return auth()->user() ?: app('carter.auth.model');
    }

    public function charge()
    {
        $plan = config('carter.shopify.plan');

        $charge = Shopify::recurringCharge();

        return $charge->confirm($charge->create($plan));
    }

    public function activate($chargeId)
    {
        $response = Shopify::recurringCharge($chargeId)->activate();

        if ($response->getStatusCode() !== static::HTTP_OK) {
            throw new \Exception();
        }

        $this->user()->update(['charge_id' => $chargeId]);
    }

}
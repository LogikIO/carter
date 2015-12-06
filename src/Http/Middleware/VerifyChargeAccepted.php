<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Woolf\Carter\RegistersStore;

class VerifyChargeAccepted
{
    private $store;
    private $auth;

    public function __construct(RegistersStore $store, Guard $auth)
    {
        $this->store = $store;
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if (! $this->store->hasAcceptedCharge($this->auth->user()->charge_id)) {
            return view('carter.shopify.store.charge');
        }

        return $next($request);
    }
}
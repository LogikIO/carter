<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Shopify;
use Woolf\Carter\Shopify\Resource\RecurringApplicationCharge;

class VerifyChargeAccepted
{

    protected $charge;

    public function __construct(RecurringApplicationCharge $charge)
    {
        $this->charge = $charge;
    }

    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (! $user->charge_id || ! $this->charge->isAccepted($user->charge_id)) {
            return $this->createNewCharge();
        }

        return $next($request);
    }

    protected function createNewCharge()
    {
        $charge = $this->charge->create(config('carter.shopify.plan'));

        return view('carter::shopify.auth.charge', ['redirect' => $charge['confirmation_url']]);
    }
}
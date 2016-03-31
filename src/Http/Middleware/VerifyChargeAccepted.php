<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Shopify;

class VerifyChargeAccepted
{

    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (! $user->charge_id || ! Shopify::recurringCharge($user->charge_id)->isAccepted()) {
            $charge = Shopify::recurringCharge()->create(config('carter.shopify.plan'));

            $redirect = Shopify::recurringCharge()->confirm($charge)->getTargetUrl();

            return view('carter::shopify.auth.charge', compact('redirect'));
        }

        return $next($request);
    }
}
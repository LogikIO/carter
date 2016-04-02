<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Shopify;

class VerifyChargeAccepted
{

    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        $charges = Shopify::resource('recurring_charges');

        if (! $user->charge_id || ! $charges->setId($user->charge_id)->isAccepted()) {
            $charge = $charges->create(config('carter.shopify.plan'));

            return view('carter::shopify.auth.charge', ['redirect' => $charge['confirmation_url']]);
        }

        return $next($request);
    }
}
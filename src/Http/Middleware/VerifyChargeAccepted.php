<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Shopify;
use Woolf\Carter\RegisterShop;

class VerifyChargeAccepted
{

    protected $shop;

    public function __construct(RegisterShop $shop)
    {
        $this->shop = $shop;
    }

    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (! $user->charge_id || ! Shopify::recurringCharge($user->charge_id)->isAccepted()) {
            $redirect = $this->shop->charge()->getTargetUrl();

            return view('carter::shopify.auth.charge', compact('redirect'));
        }

        return $next($request);
    }
}
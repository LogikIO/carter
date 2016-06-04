<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;

class RequestHasChargeId
{
    public function handle($request, Closure $next)
    {
        if (! $request->has('charge_id')) {
            return redirect()->route('shopify.signup')->withErrors('Charge ID required');
        }

        return $next($request);
    }
}
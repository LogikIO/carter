<?php

namespace NickyWoolf\Carter\Laravel\Middleware;

use Closure;

class RequestHasChargeId
{
    public function handle($request, Closure $next)
    {
        if (! $request->has('charge_id')) {
            return redirect()->route('shopify.signup')->withErrors('Request missing application charge ID');
        }

        return $next($request);
    }
}
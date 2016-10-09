<?php

namespace Woolf\Carter\Laravel\Middleware;

use Closure;

class RequestHasShopifySignature
{
    public function handle($request, Closure $next)
    {
        if (! $request->has('state') || ! $request->has('hmac') || ! $request->has('code')) {
            return redirect()->route('shopify.signup')->withErrors('Invalid request');
        }

        return $next($request);
    }
}
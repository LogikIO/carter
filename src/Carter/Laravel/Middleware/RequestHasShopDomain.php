<?php

namespace NickyWoolf\Carter\Laravel\Middleware;

use Closure;

class RequestHasShopDomain
{
    public function handle($request, Closure $next)
    {
        if (! $request->has('shop')) {
            return redirect()->route('shopify.signup')->withErrors('Shopify store domain required');
        }

        return $next($request);
    }
}
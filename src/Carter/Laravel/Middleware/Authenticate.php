<?php

namespace NickyWoolf\Carter\Laravel\Middleware;

use Closure;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            if (! $request->get('shop')) {
                return redirect()->route('shopify.signup');
            }

            return redirect()->route('shopify.login.redirect');
        }

        return $next($request);
    }
}
<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Shopify;

class RedirectToLogin
{

    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return view('carter::shopify.auth.login', [
                'redirect' => Shopify::authorizationUrl(route('shopify.login'))
            ]);
        }

        return $next($request);
    }
}
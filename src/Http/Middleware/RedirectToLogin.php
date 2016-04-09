<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Shopify;
use Woolf\Shophpify\Resource\OAuth;

class RedirectToLogin
{

    public function handle($request, Closure $next, OAuth $oauth)
    {
        if (auth()->guest()) {
            $redirect = $oauth->authorizationUrl(
                config('carter.shopify.client_id'),
                implode(',', config('carter.shopify.scopes')),
                route('shopify.register'),
                session('state')
            );

            return view('carter::shopify.auth.login', compact('redirect'));
        }

        return $next($request);
    }
}
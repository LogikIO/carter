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

            return view('carter::shopify.redirect_escape_iframe', [
                'redirect' => shopify_auth_url(route('shopify.login'))
            ]);
        }

        return $next($request);
    }
}
<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Shopify;

class RedirectToLogin
{

    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return view('carter::shopify.auth.login', ['redirect' => $this->authorizationUrl()]);
        }

        return $next($request);
    }

    protected function authorizationUrl()
    {
        return Shopify::oauth()->authorize(route('shopify.login'))->getTargetUrl();
    }
}
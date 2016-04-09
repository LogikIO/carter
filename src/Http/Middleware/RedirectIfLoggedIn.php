<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfLoggedIn
{

    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            return redirect()->route('shopify.dashboard');
        }

        return $next($request);
    }
}
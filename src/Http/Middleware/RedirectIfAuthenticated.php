<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;

class RedirectIfAuthenticated
{

    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            return redirect()->route('shopify.dashboard');
        }

        return $next($request);
    }
}
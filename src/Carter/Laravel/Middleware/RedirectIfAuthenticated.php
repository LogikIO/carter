<?php

namespace Woolf\Carter\Laravel\Middleware;

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
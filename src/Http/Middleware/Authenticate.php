<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Shopify;
use Woolf\Shophpify\Resource\OAuth;

class Authenticate
{
    protected $oauth;

    public function __construct(OAuth $oauth)
    {
        $this->oauth = $oauth;
    }

    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            $redirect = carter_auth_url();

            return view('carter::shopify.redirect_escape_iframe', compact('redirect'));
        }

        return $next($request);
    }
}
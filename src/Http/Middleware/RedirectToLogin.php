<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Woolf\Carter\ShopifyProvider;

class RedirectToLogin
{
    protected $auth;
    protected $shopify;

    public function __construct(Guard $auth, ShopifyProvider $shopify)
    {
        $this->auth = $auth;
        $this->shopify = $shopify;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            $redirect = $this->shopify->authorize(route('shopify.login'));

            return view('carter::shopify.auth.login', ['redirect' => $redirect->getTargetUrl()]);
        }

        return $next($request);
    }
}
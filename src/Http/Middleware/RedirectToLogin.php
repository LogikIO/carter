<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Woolf\Carter\Shopify\Shopify;

class RedirectToLogin
{
    protected $auth;
    protected $shopify;

    public function __construct(Guard $auth, Shopify $shopify)
    {
        $this->auth = $auth;

        $this->shopify = $shopify;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            return view('carter::shopify.auth.login', [
                'redirect' => $this->authorizationUrl()
            ]);
        }

        return $next($request);
    }

    protected function authorizationUrl()
    {
        return $this->shopify->authorize(route('shopify.login'))->getTargetUrl();
    }
}
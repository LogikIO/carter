<?php

namespace NickyWoolf\Carter\Laravel\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use NickyWoolf\Carter\Shopify\Signature;

class CheckInstallAuthorizationCode
{
    protected $signature;

    protected $config;

    public function __construct(Signature $signature, Repository $config)
    {
        $this->signature = $signature;
        $this->config = $config;
    }

    public function handle($request, Closure $next)
    {
        if (! $request->has('state') || ! $request->has('hmac') || ! $request->has('code')) {
            return redirect()->route('shopify.signup')->withErrors('Invalid request');
        }

        if (! $this->validHmac() || ! $this->validHostname()) {
            app()->abort(403, 'Client Error: 403 - Invalid Signature');
        }

        return $next($request);
    }

    protected function validHmac()
    {
        return $this->signature->hasValidHmac($this->config->get('carter.shopify.client_secret'));
    }

    protected function validHostname()
    {
        return $this->signature->hasValidHostname();
    }
}
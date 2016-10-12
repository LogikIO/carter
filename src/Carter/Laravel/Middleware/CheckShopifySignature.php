<?php

namespace NickyWoolf\Carter\Laravel\Middleware;

use Closure;
use NickyWoolf\Carter\Shopify\Signature;

class CheckShopifySignature
{
    protected $signature;

    public function __construct(Signature $signature)
    {
        $this->signature = $signature;
    }

    public function handle($request, Closure $next)
    {
        if (! $request->has('hmac') || ! $this->validHmac()) {
            app()->abort(403, 'Client Error: 403 - Invalid Signature');
        }

        return $next($request);
    }

    protected function validHmac()
    {
        return $this->signature->hasValidHmac(config('carter.shopify.client_secret'));
    }
}
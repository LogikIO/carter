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
        if (! $this->validHmac($request)) {
            app()->abort(403, 'Client Error: 403 - Invalid Signature');
        }

        return $next($request);
    }

    protected function validHmac($request)
    {
        if (! $request->has(['hmac', 'code', 'shop'])) {
            return false;
        }

        $secret = config('carter.shopify.client_secret');

        return $this->signature->hasValidHmac($request->hmac, $secret);
    }
}
<?php

namespace NickyWoolf\Carter\Laravel\Middleware;

use Closure;
use NickyWoolf\Carter\Shopify\Signature;

class CheckWebhookSignature
{
    protected $signature;

    public function __construct(Signature $signature)
    {
        $this->signature = $signature;
    }

    public function handle($request, Closure $next)
    {
        $this->signature->setHmacHeader($request->header($this->signature->hmacHeader()));

        if (! $this->signature->hasValidHmac(config('carter.shopify.client_secret'))) {
            app()->abort(403, 'Client Error: 403 - Invalid Signature');
        }

        return $next($request);
    }
}
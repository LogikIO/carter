<?php

namespace Woolf\Carter\Laravel\Middleware;

use Closure;
use NickyWoolf\Carter\Shopify\Signature;

class VerifyState
{
    protected $signature;

    public function __construct(Signature $signature)
    {
        $this->signature = $signature;
    }

    public function handle($request, Closure $next)
    {
        if (! $this->signature->hasValidNonce($request->input('state'))) {
            app()->abort(403, 'Client Error: 403 - Invalid State');
        }

        return $next($request);
    }
}
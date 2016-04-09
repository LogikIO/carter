<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Woolf\Shophpify\Signature;

class VerifySignature
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
        $hasValidHmac = $this->signature->hasValidHmac(
            $request->get('hmac'),
            $this->config->get('carter.shopify.client_secret')
        );

        $hasValidNonce = $this->signature->hasValidNonce($request->get('state'));

        $hasValidHostname = $this->signature->hasValidHostname();

        if (! ($hasValidHmac && $hasValidNonce && $hasValidHostname)) {
            app()->abort(403, 'Client Error: 403 - Invalid Signature');
        }

        return $next($request);
    }
}
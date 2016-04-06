<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Woolf\Shophpify\Signature;

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
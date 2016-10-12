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
        $header = $request->header('X-Shopify-Hmac-SHA256');
        $data = file_get_contents('php://input');
        $secret = config('carter.shopify.client_secret');

        if (! $this->signature->hasValidWebhookHmac($header, $data, $secret)) {
            app()->abort(403, 'Client Error: 403 - Invalid Signature');
        }

        return $next($request);
    }
}
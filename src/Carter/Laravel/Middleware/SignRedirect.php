<?php

namespace NickyWoolf\Carter\Laravel\Middleware;

use Closure;
use Illuminate\Support\Str;
use NickyWoolf\Carter\Shopify\Signature;

class SignRedirect
{
    protected $signature;

    public function __construct(Signature $signature)
    {
        $this->signature = $signature;
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $url = $response->getTargetUrl();
        $signature = $this->signature->sign(config('carter.shopify.client_secret'));
        $target = Str::contains($url, '?') ? "{$url}&{$signature}" : "{$url}?{$signature}";

        return $response->setTargetUrl($target);
    }
}
<?php

namespace NickyWoolf\Carter\Laravel\Middleware;

use Illuminate\Http\RedirectResponse;
use NickyWoolf\Carter\Shopify\Signature;

class SignRedirectTest extends \TestCase
{
    /** @test */
    function it_adds_shopify_hmac_signature_to_redirect_url()
    {
        $redirect = new RedirectResponse('foo/bar');
        $middleware = new SignRedirect(new Signature([]));
        $response = $middleware->handle($redirect, function ($r) {
            return $r;
        });

        $this->assertRegExp(
            '/foo\/bar\?internal=true&timestamp=\d*&hmac=[0-9a-z]*/',
            $response->getTargetUrl()
        );
    }

    /** @test */
    function it_adds_signature_to_existing_query_string()
    {
        $redirect = new RedirectResponse('foo/bar?baz=qux');
        $middleware = new SignRedirect(new Signature([]));
        $response = $middleware->handle($redirect, function ($r) {
            return $r;
        });

        $this->assertRegExp(
            '/foo\/bar\?baz=qux&internal=true&timestamp=\d*&hmac=[0-9a-z]*/',
            $response->getTargetUrl()
        );
    }

    /** @test */
    function it_creates_a_valid_signature()
    {
        $redirect = new RedirectResponse('foo/bar?baz=qux');
        $middleware = new SignRedirect(new Signature(['baz' => 'qux']));

        $response = $middleware->handle($redirect, function ($r) {
            return $r;
        });

        list($url, $query) = explode('?', $response->getTargetUrl());
        parse_str($query, $request);
        $signature = new Signature($request);

        $this->assertTrue($signature->hasValidHmac(config('carter.shopify.client_secret')));
    }
}

if (! function_exists('\NickyWoolf\Carter\Laravel\Middleware\config')) {
    function config($key = 'carter.shopify.client_secret') {
        return 'client_secret';
    }
}

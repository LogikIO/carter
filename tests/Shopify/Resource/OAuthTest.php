<?php

use Mockery as m;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Woolf\Carter\Shopify\Client;
use Woolf\Carter\Shopify\Endpoint;
use Woolf\Carter\Shopify\Resource\OAuth;
use Woolf\Carter\Tests\TestCase;

class OAuthTest extends TestCase
{
    /** @test */
    function it_requests_access_to_shop()
    {
        $shop = new OAuth(
            new Endpoint('shop.domain'),
            m::mock(Client::class),
            'access_token'
        );

        $this->assertInstanceOf(
            RedirectResponse::class,
            $shop->authorize('A', 'B', 'C', 'D')
        );
        $this->assertEquals(
            'https://shop.domain/admin/oauth/authorize?client_id=A&scope=B&redirect_uri=C&state=D',
            $shop->authorize('A', 'B', 'C', 'D')->getTargetUrl()
        );
    }
}
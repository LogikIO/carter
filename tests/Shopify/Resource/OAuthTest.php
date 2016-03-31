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
        $oauth = new OAuth(
            new Endpoint('shop.domain'),
            m::mock(Client::class)
        );

        $oauth->setConfig([
            'client_id'    => 'A',
            'scope'        => 'B',
            'redirect_uri' => 'C',
            'state'        => 'D'
        ]);

        $this->assertInstanceOf(
            RedirectResponse::class,
            $oauth->authorize()
        );
        $this->assertEquals(
            'https://shop.domain/admin/oauth/authorize?client_id=A&scope=B&redirect_uri=C&state=D',
            $oauth->authorize()->getTargetUrl()
        );
    }

    /** @test */
    function it_requests_access_token_for_shop()
    {
        $client = m::mock(Client::class, function ($mock) {
            $mock->shouldReceive('create')->andReturnSelf();
        });
        $client->shouldReceive('post')->with(
            'https://shop.domain/admin/oauth/access_token',
            [
                'headers'     => ['Accept' => 'application/json'],
                'form_params' => [
                    'client_id'     => 'A',
                    'client_secret' => 'B',
                    'code'          => 'C',
                ]
            ]
        )->andReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode(['access_token' => 'foo'])));

        $oauth = new OAuth(
            new Endpoint('shop.domain'),
            $client
        );

        $oauth->setConfig([
            'client_id'     => 'A',
            'client_secret' => 'B',
            'code'          => 'C'
        ]);

        $oauth->token();
    }
}
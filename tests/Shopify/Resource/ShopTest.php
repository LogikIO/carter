<?php

use Mockery as m;
use Woolf\Carter\Shopify\Client;
use Woolf\Carter\Shopify\Endpoint;
use Woolf\Carter\Shopify\Resource\Shop;
use Woolf\Carter\Tests\TestCase;

class ShopTest extends TestCase
{
    /** @test */
    function it_gets_all_products_for_shop()
    {
        $client = m::mock(Client::class, function ($mock) {
            $mock->shouldReceive('create')->andReturnSelf();
        });
        $client->shouldReceive('get')->with(
            'https://shop.domain/admin/shop.json?fields=id,name',
            ['headers' => ['X-Shopify-Access-Token' => 'access_token']]
        )->andReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode(['shop' => []])));

        $shop = new Shop(new Endpoint('shop.domain'), $client, 'access_token');

        $shop->get(['id', 'name']);
    }
}
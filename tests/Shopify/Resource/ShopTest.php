<?php

use Mockery as m;
use Woolf\Carter\Shopify\Endpoint;
use Woolf\Carter\Shopify\Resource\Shop;
use Woolf\Carter\Tests\TestCase;

class ShopTest extends TestCase
{
    /** @test */
    function it_gets_all_products_for_shop()
    {
        $client = function () {
            $mock = m::mock(\GuzzleHttp\Client::class);

            $mock->shouldReceive('get')->with(
                'https://shop.domain/admin/shop.json?fields=id,name',
                ['headers' => ['X-Shopify-Access-Token' => 'access_token']]
            );

            return $mock;
        };

        $shop = new Shop(new Endpoint('shop.domain'), $client, 'access_token');

        $shop->get(['id', 'name']);
    }
}
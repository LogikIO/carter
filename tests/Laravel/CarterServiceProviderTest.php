<?php

use Illuminate\Container\Container;
use NickyWoolf\Carter\Laravel\CarterServiceProvider;
use NickyWoolf\Carter\Shopify\Client;
use NickyWoolf\Carter\Shopify\Oauth;
use NickyWoolf\Carter\Shopify\Signature;

class CarterServiceProviderTest extends TestCase
{
    /** @test */
    function it_can_create_a_shop_domain_object()
    {
        $app = new Container();
        $provider = new CarterServiceProviderStub($app);
        $provider->register();

        $resource = $app->make(Oauth::class);

        $this->assertEquals('https://foo-bar/admin/oauth', $resource->endpoint('oauth'));
    }

    /** @test */
    function it_can_give_access_token_to_client()
    {
        $app = new Container();
        $provider = new CarterServiceProviderStub($app);
        $provider->register();

        $client = $app->make(Client::class);

        $this->assertEquals('ACCESS_TOKEN', $client->getAccessToken());
    }

    /** @test */
    function it_gives_request_array_to_signature()
    {
        $app = new Container();
        $provider = new CarterServiceProviderStub($app);
        $provider->register();

        $client = $app->make(Signature::class);
    }
}

class CarterServiceProviderStub extends CarterServiceProvider
{
    protected function domain()
    {
        return 'foo-bar';
    }

    protected function accessToken()
    {
        return 'ACCESS_TOKEN';
    }

    protected function request()
    {
        return [
            'foo' => 'bar'
        ];
    }
}
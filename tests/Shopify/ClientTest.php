<?php


use GuzzleHttp\Client as GuzzleClient;
use NickyWoolf\Carter\Shopify\Client;

class ClientTest extends TestCase
{
    /** @test */
    function it_builds_shopify_headers_with_access_token()
    {
        $client = new Client('TOKEN');

        $this->assertEquals(
            ['headers' => ['X-Shopify-Access-Token' => 'TOKEN']],
            $client->tokenHeader()
        );
    }

    /** @test */
    function it_returns_a_response()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('get')->with('foo/bar/baz', [])
            ->once()->andReturn('RESPONSE');

        $client = new Client(null, $http);

        $this->assertEquals('RESPONSE', $client->get('foo/bar/baz'));
    }

    /** @test */
    function it_can_get_a_resource()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('get')->with('foo/bar/baz', [])->once();

        $client = new Client(null, $http);

        $client->get('foo/bar/baz');
    }

    /** @test */
    function it_can_get_a_resource_with_options()
    {
       $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('get')->with('foo/bar/baz', ['form_params' => ['foo' => 'bar']])->once();

        $client = new Client(null, $http);

        $client->get('foo/bar/baz', ['foo' => 'bar']);
    }

    /** @test */
    function it_can_post_a_resource_with_options()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('post')->with('foo/bar/baz', ['form_params' => ['foo' => 'bar']])->once();

        $client = new NickyWoolf\Carter\Shopify\Client(null, $http);

        $client->post('foo/bar/baz', ['foo' => 'bar']);
    }

    /** @test */
    function it_can_put_a_resource_with_options()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('put')->with('foo/bar/baz', ['form_params' => ['foo' => 'bar']])->once();

        $client = new Client(null, $http);

        $client->put('foo/bar/baz', ['foo' => 'bar']);
    }

    /** @test */
    function it_can_delete_a_resource_with_options()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('delete')->with('foo/bar/baz', ['form_params' => ['foo' => 'bar']])->once();

        $client = new Client(null, $http);

        $client->delete('foo/bar/baz', ['foo' => 'bar']);
    }

    /** @test */
    function it_throws_exception_for_bad_http_verb()
    {
        $client = new Client;

        $this->setExpectedException(BadMethodCallException::class);

        $client->patch('foo/bar/baz');
    }
}
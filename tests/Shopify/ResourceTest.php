<?php

use GuzzleHttp\Psr7\Response;
use NickyWoolf\Carter\Shopify\Client;
use NickyWoolf\Carter\Shopify\Domain;
use NickyWoolf\Carter\Shopify\Resource;

class ResourceTest extends TestCase
{
    protected function resource($client = null)
    {
        return new Resource(new Domain('domain.myshopify.com'), $client ?: $this->client());
    }

    protected function client()
    {
       return Mockery::mock(Client::class);
    }

    /** @test */
    function it_creates_an_api_endpoint()
    {
        $this->assertEquals(
            'https://domain.myshopify.com/admin/foo/bar',
            $this->resource()->endpoint('foo/bar')
        );
    }

    /** @test */
    function it_removes_extra_slashes_from_path()
    {
        $this->assertEquals(
            'https://domain.myshopify.com/admin/foo/bar',
            $this->resource()->endpoint('/foo/bar/')
        );
    }

    /** @test */
    function it_adds_a_query_string()
    {
        $this->assertEquals(
            'https://domain.myshopify.com/admin/foo/bar?baz=qux&quux=1',
            $this->resource()->endpoint('/foo/bar/', ['baz' => 'qux', 'quux' => true])
        );
    }

    /** @test */
    function it_can_decode_and_parse_response_object()
    {
        $extracted = ['foo' => 'bar'];
        $response = new Response(200, [], json_encode(['works' => $extracted]));

        $this->assertEquals(['works' => $extracted], $this->resource()->parse($response));

        $this->assertEquals($extracted, $this->resource()->parse($response, 'works'));

        $this->assertFalse($this->resource()->parse($response, 'key_does_not_exist'));
    }

    /** @test */
    function it_can_make_an_http_get_request()
    {
        $client = $this->client();
        $client->shouldReceive('get')
            ->with('https://domain.myshopify.com/admin/foo/bar', [])
            ->once()->andReturn(new Response());

        $resource = $this->resource($client);

        $resource->httpGet(['path' => 'foo/bar']);
    }
}
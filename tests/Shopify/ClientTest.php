<?php


use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use NickyWoolf\Carter\Shopify\Client;

class ClientTest extends TestCase
{
    protected function client($args = [])
    {
        $domain = isset($args['domain']) ? $args['domain'] : 'domain.myshopify.com';
        $accessToken = isset($args['token']) ? $args['token'] : 'TOKEN';
        $http = isset($args['http']) ? $args['http'] : null;

        return new Client($domain, $accessToken, $http);
    }

    protected function response()
    {
        return new Response();
    }

    /** @test */
    function it_builds_shopify_headers_with_access_token()
    {
        $this->assertEquals(
            ['headers' => ['X-Shopify-Access-Token' => 'TOKEN']],
            $this->client()->tokenHeader()
        );
    }

    /** @test */
    function it_can_get_a_resource()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('get')
            ->with('https://domain.myshopify.com/admin/foo/bar/baz', [])
            ->once()->andReturn($this->response());

        $this->client(['http' => $http])->get(['path' => 'foo/bar/baz']);
    }

    /** @test */
    function it_can_get_a_resource_with_options()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('get')
            ->with('https://domain.myshopify.com/admin/foo/bar/baz?foo=bar', [])
            ->once()->andReturn($this->response());

        $this->client(['http' => $http])->get([
            'path' => 'foo/bar/baz',
            'query' => ['foo' => 'bar']
        ]);
    }

    /** @test */
    function it_can_post_a_resource_with_options()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('post')
            ->with('https://domain.myshopify.com/admin/foo/bar/baz', ['form_params' => ['foo' => 'bar']])
            ->once()->andReturn($this->response());

        $this->client(['http' => $http])->post([
            'path'    => 'foo/bar/baz',
            'options' => ['foo' => 'bar']
        ]);
    }

    /** @test */
    function it_can_put_a_resource_with_options()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('put')
            ->with('https://domain.myshopify.com/admin/foo/bar/baz', ['form_params' => ['foo' => 'bar']])
            ->once()->andReturn($this->response());

        $this->client(['http' => $http])->put([
            'path'    => 'foo/bar/baz',
            'options' => ['foo' => 'bar']
        ]);
    }

    /** @test */
    function it_can_delete_a_resource_with_options()
    {
        $http = Mockery::mock(GuzzleClient::class);
        $http->shouldReceive('delete')
            ->with('https://domain.myshopify.com/admin/foo/bar/baz', ['form_params' => ['foo' => 'bar']])
            ->once()->andReturn($this->response());

        $this->client(['http' => $http])->delete([
            'path'    => 'foo/bar/baz',
            'options' => ['foo' => 'bar']
        ]);
    }

    /** @test */
    function it_creates_an_api_endpoint()
    {
        $this->assertEquals(
            'https://domain.myshopify.com/admin/foo/bar',
            $this->client()->endpoint('foo/bar')
        );
    }

    /** @test */
    function it_removes_extra_slashes_from_path()
    {
        $this->assertEquals(
            'https://domain.myshopify.com/admin/foo/bar',
            $this->client()->endpoint('/foo/bar/')
        );
    }

    /** @test */
    function it_adds_a_query_string()
    {
        $this->assertEquals(
            'https://domain.myshopify.com/admin/foo/bar?baz=qux&quux=1',
            $this->client()->endpoint('/foo/bar/', ['baz' => 'qux', 'quux' => true])
        );
    }

    /** @test */
    function it_can_decode_and_parse_response_object()
    {
        $extracted = ['foo' => 'bar'];
        $response = new Response(200, [], json_encode(['works' => $extracted]));

        $this->assertEquals(['works' => $extracted], $this->client()->parse($response));

        $this->assertEquals($extracted, $this->client()->parse($response, 'works'));

        $this->assertFalse($this->client()->parse($response, 'key_does_not_exist'));
    }
}
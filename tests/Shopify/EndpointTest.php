<?php

use Woolf\Carter\Tests\TestCase;

class EndpointTest extends TestCase
{
    /** @test */
    function it_builds_endpoint_for_shop_domain()
    {
        $endpoint = new Woolf\Carter\Shopify\Endpoint('foo.bar');

        $this->assertEquals('https://foo.bar/some/path', $endpoint->build('/some/path'));
    }

    /** @test */
    function it_adds_slash_between_domain_and_path()
    {
        $endpoint = new Woolf\Carter\Shopify\Endpoint('foo.bar');

        $this->assertEquals('https://foo.bar/some/path', $endpoint->build('some/path'));
    }

    /** @test */
    function it_adds_query_string_when_given_an_array()
    {
        $endpoint = new Woolf\Carter\Shopify\Endpoint('foo.bar');

        $query = [
            'baz'  => 'qux',
            'this' => 'that'
        ];

        $this->assertEquals('https://foo.bar/some/path?baz=qux&this=that', $endpoint->build('some/path', $query));
    }

    /** @test */
    function it_add_query_string_when_given_an_object()
    {
        $endpoint = new Woolf\Carter\Shopify\Endpoint('foo.bar');

        $query = new stdClass();
        $query->baz = 'qux';
        $query->this = 'that';

        $this->assertEquals('https://foo.bar/some/path?baz=qux&this=that', $endpoint->build('some/path', $query));
    }

    /** @test */
    function it_does_not_encode_commas()
    {
        $endpoint = new Woolf\Carter\Shopify\Endpoint('foo.bar');

        $query = ['baz' => 'qux,this,that'];

        $this->assertEquals('https://foo.bar/some/path?baz=qux,this,that', $endpoint->build('some/path', $query));
    }
}
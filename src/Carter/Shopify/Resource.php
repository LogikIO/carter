<?php

namespace NickyWoolf\Carter\Shopify;

use Psr\Http\Message\ResponseInterface;

class Resource
{
    /**
     * @var Domain
     */
    protected $domain;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Resource constructor.
     * @param Domain $domain
     * @param Client $client
     */
    public function __construct(Domain $domain, Client $client)
    {
        $this->domain = $domain;
        $this->client = $client;
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function httpGet($args)
    {
        $args['verb'] = 'get';

        return $this->http($args);
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function httpPost($args)
    {
        $args['verb'] = 'post';

        return $this->http($args);
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function httpPut($args)
    {
        $args['verb'] = 'put';

        return $this->http($args);
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function httpDelete($args)
    {
        $args['verb'] = 'delete';

        return $this->http($args);
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function http($args)
    {
        $endpoint = $this->endpoint(
            $args['path'],
            (isset($args['query']) && $args['query']) ? $args['query'] : false
        );

        $response = $this->client->{$args['verb']}(
            $endpoint,
            isset($args['options']) ? $args['options'] : []
        );

        return $this->parse(
            $response,
            isset($args['extract']) ? $args['extract'] : false
        );
    }

    /**
     * @param string $path
     * @param bool $query
     * @return string
     */
    public function endpoint($path, $query = false)
    {
        $url = "https://{$this->domain}/admin/".trim($path, '/');

        if (! $query) {
            return $url;
        }

        return $url.'?'.urldecode(http_build_query($query, '', '&'));
    }

    /**
     * @param ResponseInterface $response
     * @param bool $extract
     * @return bool|mixed|ResponseInterface
     */
    public function parse(ResponseInterface $response, $extract = false)
    {
        $response = json_decode($response->getBody(), true);

        if (! $extract) {
            return $response;
        }

        return isset($response[$extract]) ? $response[$extract] : false;
    }
}

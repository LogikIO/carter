<?php

namespace NickyWoolf\Carter\Shopify;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var null|string
     */
    protected $accessToken;

    /**
     * @var null|object
     */
    protected $client;

    /**
     * Client constructor.
     * @param string $domain
     * @param null|string $accessToken
     * @param null|object $client
     */
    public function __construct($domain, $accessToken = null, $client = null)
    {
        $this->domain = $domain;
        $this->accessToken = $accessToken;
        $this->client = $client;
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function get($args)
    {
        $args['verb'] = 'get';

        return $this->http($args);
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function post($args)
    {
        $args['verb'] = 'post';

        return $this->http($args);
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function put($args)
    {
        $args['verb'] = 'put';

        return $this->http($args);
    }

    /**
     * @param $args
     * @return bool|mixed|ResponseInterface
     */
    public function delete($args)
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
        $endpoint = $this->endpoint($args['path'], $this->pluck('query', $args));
        $options = $this->prepare($this->pluck('options', $args, []));

        $response = $this->client()->{$args['verb']}($endpoint, $options);

        return $this->parse($response, $this->pluck('extract', $args));
    }

    /**
     * @param $key
     * @param $items
     * @param mixed $default
     * @return mixed
     */
    protected function pluck($key, $items, $default = false)
    {
        return (isset($items[$key])) ? $items[$key] : $default;
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

    /**
     * @param array $options
     * @return array
     */
    protected function prepare(array $options = [])
    {
        return $options ? ['form_params' => $options] : $options;
    }

    /**
     * @return object|GuzzleClient
     */
    public function client()
    {
        return $this->client ?: new GuzzleClient($this->tokenHeader());
    }

    /**
     * @return array
     */
    public function tokenHeader()
    {
        return ($token = $this->getAccessToken()) ? ['headers' => ['X-Shopify-Access-Token' => $token]] : [];
    }

    /**
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
<?php

namespace NickyWoolf\Carter\Shopify;

use BadMethodCallException;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

/**
 * @method ResponseInterface get($uri, array $options = [])
 * @method ResponseInterface put($uri, array $options = [])
 * @method ResponseInterface post($uri, array $options = [])
 * @method ResponseInterface delete($uri, array $options = [])
 */
class Client
{
    /**
     * @var null|string
     */
    protected $accessToken;

    /**
     * @var null|object
     */
    protected $http;

    /**
     * Client constructor.
     * @param null|string $accessToken
     * @param null|object $http
     */
    public function __construct($accessToken = null, $http = null)
    {
        $this->accessToken = $accessToken;
        $this->http = $http;
    }

    /**
     * @param string $name
     * @param array $args
     * @return ResponseInterface
     */
    public function __call($name, $args)
    {
        if (! in_array($name, ['get', 'post', 'put', 'delete'])) {
            throw new BadMethodCallException("Can't call '{$name}' on this object");
        }

        $uri = $args[0];
        $options = isset($args[1]) ? $this->prepare($args[1]) : [];

        return $this->http()->$name($uri, $options);
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
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return object|GuzzleClient
     */
    public function http()
    {
        return $this->http ?: new GuzzleClient($this->tokenHeader());
    }

    /**
     * @return array
     */
    public function tokenHeader()
    {
        return ($token = $this->getAccessToken()) ? ['headers' => ['X-Shopify-Access-Token' => $token]] : [];
    }
}
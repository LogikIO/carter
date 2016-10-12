<?php

namespace NickyWoolf\Carter\Shopify;

class Oauth
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $clientId
     * @param string $scope
     * @param string $redirectUri
     * @param string $state
     * @return string
     */
    public function authorizationUrl($clientId, $scope, $redirectUri, $state)
    {
        return $this->client->endpoint('oauth/authorize', [
            'client_id'    => $clientId,
            'scope'        => $scope,
            'redirect_uri' => $redirectUri,
            'state'        => $state
        ]);
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $code
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function requestAccessToken($clientId, $clientSecret, $code)
    {
        return $this->client->post([
            'path'    => 'oauth/access_token',
            'options' => [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'code'          => $code
            ],
            'extract' => 'access_token',
        ]);
    }
}

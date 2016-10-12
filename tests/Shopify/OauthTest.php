<?php

use NickyWoolf\Carter\Shopify\Client;
use NickyWoolf\Carter\Shopify\Oauth;

class OauthTest extends TestCase
{
    /** @test */
    function it_returns_the_authorization_url()
    {
        $oauth = new Oauth(new Client('shopify-domain'));

        $url = implode([
            'https://shopify-domain/admin/oauth/authorize?',
            'client_id=CLIENT_ID&',
            'scope=SCOPE&',
            'redirect_uri=REDIRECT&',
            'state=STATE'
        ]);

        $this->assertEquals($url, $oauth->authorizationUrl('CLIENT_ID', 'SCOPE', 'REDIRECT', 'STATE'));
    }
}
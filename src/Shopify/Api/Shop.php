<?php

namespace Woolf\Carter\Shopify\Api;

class Shop extends Api
{
    public function get()
    {
        $url = $this->url->build('/admin/shop.json');

        $response = $this->client->get($url, $this->tokenHeader());

        return  $this->client->parse($response, 'shop') + ['access_token' => $this->accessToken()];
    }

    public function apps()
    {
        return $this->client->redirect($this->url->build('/admin/apps'));
    }
}
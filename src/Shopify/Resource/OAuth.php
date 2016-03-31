<?php

namespace Woolf\Carter\Shopify\Resource;

class OAuth extends Resource
{
    protected $config = [
        'client_id'     => null,
        'client_secret' => null,
        'scope'         => null,
        'redirect_uri'  => null,
        'state'         => null,
        'code'          => null
    ];

    public function setConfig($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    public function authorize()
    {
        $options = [
            'client_id'    => $this->config['client_id'],
            'scope'        => $this->config['scope'],
            'redirect_uri' => $this->config['redirect_uri'],
            'state'        => $this->config['state']
        ];

        $url = $this->endpoint->build('admin/oauth/authorize', $options);

        return $this->redirect($url);
    }

    public function token()
    {
        $options = [
            'headers'     => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id'     => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
                'code'          => $this->config['code'],
            ]
        ];

        $url = $this->endpoint->build('admin/oauth/access_token');

        return $this->parse($this->client->create()->post($url, $options), 'access_token');
    }
}
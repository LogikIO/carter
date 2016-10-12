<?php

namespace NickyWoolf\Carter\Shopify;

class Signature
{
    /**
     * @var array
     */
    protected $request;

    /**
     * Signature constructor.
     * @param array $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @param $secret
     * @return array
     */
    public function sign($secret)
    {
        $request = [
            'internal' => 'true',
            'timestamp' => time()
        ];

        $request['hmac'] = $this->hash($this->message(array_merge($this->request, $request)), $secret);

        return http_build_query($request, '&');
    }

    public function hasValidWebhookHmac($header, $data, $secret)
    {
        return $header === base64_encode($this->hash($data, $secret, true));
    }

    /**
     * @param string $secret
     * @return bool
     */
    public function hasValidHmac($secret)
    {
        return $this->request['hmac'] === $this->hash($this->message(), $secret, false);
    }

    /**
     * @param $message
     * @param $secret
     * @param bool $raw
     * @return string
     */
    protected function hash($message, $secret, $raw = false)
    {
        return hash_hmac($this->hashingAlgorithm(), $message, $secret, $raw);
    }

    /**
     * @param null $request
     * @return string
     */
    protected function message($request = null)
    {
        $keysToRemove = ['signature', 'hmac'];

        $parameters = array_diff_key($request ?: $this->request, array_flip($keysToRemove));

        return urldecode(http_build_query($parameters));
    }

    /**
     * @return string
     */
    protected function hashingAlgorithm()
    {
        return 'sha256';
    }

    /**
     * @return bool
     */
    public function hasValidHostname()
    {
        return !! preg_match($this->validShopPattern(), $this->request['shop']);
    }

    /**
     * @return string
     */
    protected function validShopPattern()
    {
        return '/^([a-z]|[0-9]|\.|-)+myshopify.com$/i';
    }

    /**
     * @param string $state
     * @return bool
     */
    public function hasValidNonce($state)
    {
        return (strlen($state) && $state === $this->request['state']);
    }
}
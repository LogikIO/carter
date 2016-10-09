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
     * @param string $hmac
     * @param string $secret
     * @return bool
     */
    public function hasValidHmac($hmac, $secret)
    {
        return ($hmac === hash_hmac($this->hashingAlgorithm(), $this->message(), $secret));
    }

    /**
     * @return string
     */
    protected function message()
    {
        $keysToRemove = ['signature', 'hmac'];

        $parameters = array_diff_key($this->request, array_flip($keysToRemove));

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
<?php

namespace NickyWoolf\Carter\Shopify;

class Domain
{
    protected $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function __toString()
    {
        return $this->domain;
    }
}
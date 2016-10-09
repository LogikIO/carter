<?php

namespace NickyWoolf\Carter\Laravel;

trait ShopifyCustomer
{
    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = encrypt($value);
    }

    public function getAccessTokenAttribute()
    {
        return decrypt($this->attributes['access_token']);
    }
}
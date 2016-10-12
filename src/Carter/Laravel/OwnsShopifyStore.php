<?php

namespace NickyWoolf\Carter\Laravel;

trait OwnsShopifyStore
{
    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = encrypt($value);
    }

    public function getAccessTokenAttribute()
    {
        return decrypt($this->attributes['access_token']);
    }

    public function isActive()
    {
        return (bool) $this->installed;
    }

    public function install()
    {
        $this->installed = true;
    }

    public function uninstall()
    {
        $this->installed = false;
    }
}
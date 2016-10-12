<?php

namespace NickyWoolf\Carter\Laravel;

trait OwnsShopifyStore
{
    public function shopOwner($shop)
    {
        return static::whereDomain($shop)->first();
    }

    public function isActive()
    {
        return (bool) $this->installed;
    }

    public function install()
    {
        return $this->update(['installed' => true]);
    }

    public function uninstall()
    {
        return $this->update(['installed' => false]);
    }

    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = encrypt($value);
    }

    public function getAccessTokenAttribute()
    {
        return decrypt($this->attributes['access_token']);
    }
}
<?php

namespace Woolf\Carter;

use Crypt;

trait StoreOwner
{

    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = Crypt::encrypt($value);
    }

    public function getAccessTokenAttribute()
    {
        return Crypt::decrypt($this->attributes['access_token']);
    }

}
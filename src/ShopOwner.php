<?php

namespace Woolf\Carter;

use Illuminate\Contracts\Encryption\Encrypter;

trait ShopOwner
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
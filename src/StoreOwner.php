<?php

namespace Woolf\Carter;

use Illuminate\Contracts\Encryption\Encrypter;

trait StoreOwner
{

    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = $this->encrypter()->encrypt($value);
    }

    public function getAccessTokenAttribute()
    {
        return $this->encrypter()->decrypt($this->attributes['access_token']);
    }

    protected function encrypter()
    {
        return app(Encrypter::class);
    }

}
<?php

namespace Woolf\Carter\Shopify\Resource;

use InvalidArgumentException;

abstract class ResourceWithId extends Resource
{

    protected $id;

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    protected function haveId()
    {
        return ! is_null($this->getId());
    }

    protected function mustIncludeId()
    {
        if (! $this->haveId()) {
            throw new InvalidArgumentException('Id Missing');
        }
    }
}
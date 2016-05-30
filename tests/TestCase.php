<?php

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        if (class_exists(Mockery::class)) {
            Mockery::close();
        }
    }
}

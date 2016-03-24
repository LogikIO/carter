<?php

use Illuminate\Contracts\Encryption\Encrypter;
use Mockery as m;
use Woolf\Carter\Tests\TestCase;

class StoreOwnerTest extends TestCase
{
    /** @test */
    function it_encrypts_access_token()
    {
        $owner = new StoreOwnerDouble();

        $encrypter = m::mock(Encrypter::class);

        $encrypter->shouldReceive('encrypt')
            ->with('access_token')
            ->once()
            ->andReturn('encrypted_access_token');

        $owner->setEncrypter($encrypter)
            ->setAccessTokenAttribute('access_token');

        $this->assertEquals('encrypted_access_token', $owner->attributes['access_token']);
    }

    /** @test */
    function it_decrypts_access_token()
    {
        $owner = new StoreOwnerDouble();

        $owner->attributes = ['access_token' => 'encrypted_access_token'];

        $encrypter = m::mock(Encrypter::class);

        $encrypter->shouldReceive('decrypt')
            ->with('encrypted_access_token')
            ->once();

        $owner->setEncrypter($encrypter)
            ->getAccessTokenAttribute();
    }
}

class StoreOwnerDouble
{
    use \Woolf\Carter\StoreOwner;

    public $attributes = [];

    protected $encrypter;

    public function setEncrypter($encrypter)
    {
        $this->encrypter = $encrypter;

        return $this;
    }

    protected function encrypter()
    {
        return $this->encrypter;
    }
}
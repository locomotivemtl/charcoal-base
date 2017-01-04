<?php

namespace Charcoal\User\Tests;

use PHPUnit_Framework_TestCase;

use Psr\Log\NullLogger;

use Charcoal\Factory\GenericFactory as Factory;

use Charcoal\User\Authenticator;

class AuthenticatorTest extends PHPUnit_Framework_TestCase
{
    public $obj;

    public function setUp()
    {
        $factory = new Factory([]);

        $this->obj = new Authenticator([
            'logger'            => new NullLogger(),
            'user_type'         => 'charcoal/user/generic-user',
            'user_factory'      => $factory,
            'token_type'        => 'charcoal/user/auth-token',
            'token_factory'     => $factory
        ]);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(Authenticator::class, $this->obj);
    }
}

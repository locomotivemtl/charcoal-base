<?php

namespace Charcoal\User\Tests;

use \Psr\Log\NullLogger;

// Depentendies from phpunit
use \PHPUnit_Framework_TestCase as TestCase;

// Dependencies from `zendframework/zend-permissions`
use \Zend\Permissions\Acl\Acl;
use \Zend\Permissions\Acl\Role\GenericRole as Role;
use \Zend\Permissions\Acl\Resource\GenericResource as Resource;

use \Charcoal\User\Authorizer;

/**
 *
 */
class AuthorizerTest extends TestCase
{
    public $obj;

    public function setUp()
    {
        $acl = new Acl();
        $acl->addResource(new Resource('test'));

        $this->obj = new Authorizer([
            'logger'            => new NullLogger(),
            'acl'               => $acl,
            'resource'          => 'test'
        ]);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(Authorizer::class, $this->obj);
    }
}

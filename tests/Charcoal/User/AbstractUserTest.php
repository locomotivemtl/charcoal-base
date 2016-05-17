<?php

namespace Charcoal\User\Tests;

use \Psr\Log\NullLogger;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    public function setUp()
    {
        $container = $GLOBALS['container'];

        $metadataLoader = new \Charcoal\Model\MetadataLoader([
            'logger' => new \Psr\Log\NullLogger(),
            'base_path' => __DIR__,
            'paths' => ['metadata'],
            'config' => $GLOBALS['container']['config'],
            'cache'  => $GLOBALS['container']['cache']
        ]);

        $this->obj = $this->getMockForAbstractClass('\Charcoal\User\AbstractUser', [[
            'logger'            =>$container['logger'],
            'metadata_loader'   => $metadataLoader
        ]]);
    }

    public function testKey()
    {
        $obj = $this->obj;
        $this->assertEquals('username', $obj->key());
    }

    public function testDefaultValues()
    {
        $obj = $this->obj;
        $this->assertTrue($obj->active());
        $this->assertEquals('', $obj->loginToken());
    }

    /**
     * Assert that the `setData` method:
     * - is chainable
     * - set the various properties
     */
    public function testSetData()
    {
        $obj = $this->obj;
        $ret = $obj->setData([
            'username'=>'Foo',
            'email'=>'test@example.com',
            'loginToken'=>'token',
            'active'=>false
        ]);
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->username());
        $this->assertEquals('test@example.com', $obj->email());
        $this->assertEquals('token', $obj->loginToken());
        $this->assertFalse($obj->active());
    }

    /*public function testSetDataDoesNotSetPassword()
    {
        $obj = $this->obj;
        $this->assertNull($obj->password());
        $obj->setData(['password'=>'password123']);
        $this->assertNull($obj->password())
    }*/

    public function testSetUsername()
    {
        $obj = $this->obj;
        $ret = $obj->setUsername('Foobar');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foobar', $obj->username());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setUsername(false);
    }

    public function testSetEmail()
    {
        $obj = $this->obj;
        $ret = $obj->setEmail('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals('test@example.com', $obj->email());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setEmail(false);
    }
}

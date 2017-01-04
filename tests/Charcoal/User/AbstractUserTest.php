<?php

namespace Charcoal\User\Tests;

use PHPUnit_Framework_TestCase;

use DateTime;

use Psr\Log\NullLogger;

use Charcoal\Model\Service\MetadataLoader;

use Charcoal\User\AbstractUser;

/**
 *
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instance of object under test
     */
    public $obj;

    /**
     *
     */
    public function setUp()
    {
        $container = $GLOBALS['container'];

        $metadataLoader = new MetadataLoader([
            'logger'    => new NullLogger(),
            'base_path' => __DIR__,
            'paths'     => ['metadata'],
            'config'    => $GLOBALS['container']['config'],
            'cache'     => $GLOBALS['container']['cache']
        ]);

        $this->obj = $this->getMockForAbstractClass(AbstractUser::class, [[
            'logger'            => $container['logger'],
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
            'roles'=>[
                'foo', 'bar'
            ],
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

    /**
     *
     */
    public function testSetUsername()
    {
        $obj = $this->obj;
        $ret = $obj->setUsername('Foobar');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foobar', $obj->username());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setUsername(false);
    }

    /**
     *
     */
    public function testSetEmail()
    {
        $ret = $this->obj->setEmail('test@example.com');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('test@example.com', $this->obj->email());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setEmail(false);
    }

    /**
     *
     */
    public function testSetRoles()
    {
        $ret = $this->obj->setRoles(null);
        $this->assertSame($ret, $this->obj);
        $this->assertNull($this->obj->roles());

        $this->obj->setRoles('foo,bar');
        $this->assertEquals(['foo', 'bar'], $this->obj->roles());

        $this->obj->setRoles(['foobar', 'baz']);
        $this->assertEquals(['foobar', 'baz'], $this->obj->roles());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setRoles(false);
    }

    /**
     *
     */
    public function testSetLastLoginDate()
    {
        $ret = $this->obj->setLastLoginDate('today');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(new DateTime('today'), $this->obj->lastLoginDate());

        $this->obj->setLastLoginDate(null);
        $this->assertNull($this->obj->lastLoginDate());

        $date = new DateTime('tomorrow');
        $this->obj->setLastLoginDate($date);
        $this->assertEquals($date, $this->obj->lastLoginDate());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setLastLoginDate(false);
    }


    /**
     *
     */
    public function testSetLastLoginIp()
    {
        $ret = $this->obj->setLastLoginIp('8.8.8.8');
        $this->assertSame($ret, $this->obj);

        $this->assertEquals('8.8.8.8', $this->obj->lastLoginIp());
    }

    /**
     *
     */
    public function testSetLastPasswordDate()
    {
        $ret = $this->obj->setLastPasswordDate('today');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(new DateTime('today'), $this->obj->lastPasswordDate());

        $this->obj->setLastPasswordDate(null);
        $this->assertNull($this->obj->lastPasswordDate());

        $date = new DateTime('tomorrow');
        $this->obj->setLastPasswordDate($date);
        $this->assertEquals($date, $this->obj->lastPasswordDate());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setLastPasswordDate(false);
    }

    /**
     *
     */
    public function testSetLastPasswordIp()
    {
        $ret = $this->obj->setLastPasswordIp('8.8.8.8');
        $this->assertSame($ret, $this->obj);

        $this->assertEquals('8.8.8.8', $this->obj->lastPasswordIp());
    }

    public function testSetLoginToken()
    {
        $ret = $this->obj->setLoginToken('abc');
        $this->assertSame($ret, $this->obj);

        $this->assertEquals('abc', $this->obj->loginToken());
    }
}

<?php

namespace Charcoal\User\Tests;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    public function setUp()
    {
        $this->obj = $this->getMockForAbstractClass('\Charcoal\User\AbstractUser');
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
        $this->assertEquals('', $obj->login_token());
    }

    /**
    * Assert that the `set_data` method:
    * - is chainable
    * - set the various properties
    */
    public function testSetData()
    {
        $obj = $this->obj;
        $ret = $obj->set_data([
            'username'=>'Foo',
            'email'=>'test@example.com',
            'login_token'=>'token',
            'active'=>false
        ]);
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->username());
        $this->assertEquals('test@example.com', $obj->email());
        $this->assertEquals('token', $obj->login_token());
        $this->assertFalse($obj->active());
    }

    /*public function testSetDataDoesNotSetPassword()
    {
        $obj = $this->obj;
        $this->assertNull($obj->password());
        $obj->set_data(['password'=>'password123']);
        $this->assertNull($obj->password())
    }*/

    public function testSetUsername()
    {
        $obj = $this->obj;
        $ret = $obj->set_username('Foobar');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foobar', $obj->username());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_username(false);
    }

    public function testSetEmail()
    {
        $obj = $this->obj;
        $ret = $obj->set_email('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals('test@example.com', $obj->email());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_email(false);
    }
}

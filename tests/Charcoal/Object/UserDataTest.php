<?php

namespace Charcoal\Tests\Object;

use \DateTime as DateTime;

use \Psr\Log\NullLogger;

use \Charcoal\Object\UserData as UserData;

class UserDataTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
         $logger = new NullLogger();
         $this->obj = new UserData([
            'logger'=>$logger
         ]);
    }

    public function testConstructor()
    {
        $obj = $this->obj;
        $this->assertInstanceOf('\Charcoal\Object\UserData', $obj);

        $this->assertSame(null, $obj->ip());
        $this->assertSame(null, $obj->lang());
        $this->assertSame(null, $obj->ts());
    }

    public function testSetData()
    {
        $obj = $this->obj;
        $ret = $obj->setData(
            [
            'ip'=>'192.168.1.1',
            'lang'=>'fr',
            'ts'=>'2015-01-01 15:05:20'
            ]
        );
        $this->assertSame($ret, $obj);
        $this->assertEquals(ip2long('192.168.1.1'), $obj->ip());
        $this->assertEquals('fr', $obj->lang());
        $expected = new DateTime('2015-01-01 15:05:20');
        $this->assertEquals($expected, $obj->ts());
    }

    public function testSetIp()
    {
        $obj = $this->obj;
        $ret = $obj->setIp('1.1.1.1');
        $this->assertSame($ret, $obj);
        $this->assertEquals(ip2long('1.1.1.1'), $obj->ip());

        $obj->setIp(2349255);
        $this->assertEquals(2349255, $obj->ip());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setIp(false);
    }

    public function testSetLang()
    {
        $obj = $this->obj;
        $ret = $obj->setLang('en');
        $this->assertSame($ret, $obj);
        $this->assertEquals('en', $obj->lang());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setLang(false);
    }

    public function testSetTs()
    {
        $obj = $this->obj;
        $ret = $obj->setTs('July 1st, 2014');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('July 1st, 2014');
        $this->assertEquals($expected, $obj->ts());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setTs(false);
    }

    public function testPreSave()
    {
        $obj = $this->obj;
        $this->assertSame(null, $obj->ip());
        $this->assertSame(null, $obj->lang());
        $this->assertSame(null, $obj->ts());

        $obj->pre_save();

        $this->assertNotSame(null, $obj->ip());
        $this->assertNotSame(null, $obj->lang());
        $this->assertNotSame(null, $obj->ts());

    }
}

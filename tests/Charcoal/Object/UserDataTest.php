<?php

namespace Charcoal\Tests\Object;

use \DateTime as DateTime;

use \Charcoal\Object\UserData as UserData;

class UserDataTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new UserData();
        $this->assertInstanceOf('\Charcoal\Object\UserData', $obj);

        $this->assertSame(null, $obj->ip());
        $this->assertSame(null, $obj->lang());
        $this->assertSame(null, $obj->ts());
    }

    public function testSetData()
    {
        $obj = new UserData();
        $ret = $obj->set_data([
            'ip'=>'192.168.1.1',
            'lang'=>'fr',
            'ts'=>'2015-01-01 15:05:20'
        ]);
        $this->assertSame($ret, $obj);
        $this->assertEquals(ip2long('192.168.1.1'), $obj->ip());
        $this->assertEquals('fr', $obj->lang());
        $expected = new DateTime('2015-01-01 15:05:20');
        $this->assertEquals($expected, $obj->ts());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_data(false);
    }

    public function testSetIp()
    {
        $obj = new UserData();
        $ret = $obj->set_ip('1.1.1.1');
        $this->assertSame($ret, $obj);
        $this->assertEquals(ip2long('1.1.1.1'), $obj->ip());

        $obj->set_ip(2349255);
        $this->assertEquals(2349255, $obj->ip());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_ip(false);
    }

    public function testSetLang()
    {
        $obj = new UserData();
        $ret = $obj->set_lang('en');
        $this->assertSame($ret, $obj);
        $this->assertEquals('en', $obj->lang());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_lang(false);
    }

    public function testSetTs()
    {
        $obj = new UserData();
        $ret = $obj->set_ts('July 1st, 2014');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('July 1st, 2014');
        $this->assertEquals($expected, $obj->ts());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_ts(false);
    }

    public function testPreSave()
    {
        $obj = new UserData();
        $this->assertSame(null, $obj->ip());
        $this->assertSame(null, $obj->lang());
        $this->assertSame(null, $obj->ts());

        $obj->pre_save();

        $this->assertNotSame(null, $obj->ip());
        $this->assertNotSame(null, $obj->lang());
        $this->assertNotSame(null, $obj->ts());

    }
}

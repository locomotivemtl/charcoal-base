<?php

namespace Charcoal\Tests\Object;

use \DateTime as DateTime;

use \Psr\Log\NullLogger;

use \Charcoal\Object\Content;

class ContentTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $logger = new NullLogger();
        $this->obj = new Content([
            'logger'=>$logger
        ]);
    }

    public function testConstructor()
    {
        $obj = $this->obj;
        $this->assertInstanceOf('\Charcoal\Object\Content', $obj);
    }

    public function testSetData()
    {
        $obj = $this->obj;
        $ret = $obj->setData(
            [
            'active'=>false,
            'position'=>42,
            'created'=>'2015-01-01 13:05:45',
            'created_by'=>'Me',
            'last_modified'=>'2015-04-01 22:10:30',
            'lastModified_by'=>'You'
            ]
        );
        $this->assertSame($ret, $obj);
        $this->assertNotTrue($obj->active());
        $this->assertEquals(42, $obj->position());
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->created());
        $this->assertEquals('Me', $obj->createdBy());
        $expected = new DateTime('2015-04-01 22:10:30');
        $this->assertEquals($expected, $obj->lastModified());
        $this->assertEquals('You', $obj->lastModifiedBy());
    }

    public function testSetActive()
    {
        $obj = $this->obj;
        $this->assertTrue($obj->active());
        $ret = $obj->setActive(false);
        $this->assertSame($ret, $obj);
        $this->assertNotTrue($obj->active());
    }

    public function testSetPosition()
    {
        $obj = $this->obj;
        $this->assertEquals(0, $obj->position());
        $ret = $obj->setPosition(42);
        $this->assertSame($ret, $obj);
        $this->assertEquals(42, $obj->position());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setPosition('foo');
    }

    public function testSetCreated()
    {
        $obj = $this->obj;
        $ret = $obj->setCreated('2015-01-01 13:05:45');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->created());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setCreated(false);
    }

    public function testSetCreatedBy()
    {
        $obj = $this->obj;
        $ret = $obj->setCreatedBy('Me');
        $this->assertSame($ret, $obj);
        $this->assertEquals('Me', $obj->createdBy());

        //$this->setExpectedException('\InvalidArgumentException');
        //$obj->setCreatedBy(false);
    }

    public function testSetLastModified()
    {
        $obj = $this->obj;
        $ret = $obj->setLastModified('2015-01-01 13:05:45');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->lastModified());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setLastModified(false);
    }

    public function testSetLastModifiedBy()
    {
        $obj = $this->obj;
        $ret = $obj->setLastModifiedBy('Me');
        $this->assertSame($ret, $obj);
        $this->assertEquals('Me', $obj->lastModifiedBy());

        //$this->setExpectedException('\InvalidArgumentException');
        //$obj->setLastModifiedBy(false);
    }

    public function testSetPreSave()
    {
        $obj = $this->obj;
        $this->assertSame(null, $obj->created());
        $this->assertSame(null, $obj->lastModified());

        $obj->preSave();
        $this->assertNotSame(null, $obj->created());
        $this->assertNotSame(null, $obj->lastModified());

    }

    // public function testSetPreUpdate()
    // {
    //     $obj = $this->obj;
    //     $this->assertSame(null, $obj->lastModified());

    //     $obj->preUpdate();
    //     $this->assertNotSame(null, $obj->lastModified());

    // }
}

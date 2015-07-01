<?php

namespace Charcoal\Tests\Object;

use \DateTime as DateTime;

use \Charcoal\Object\Content as Content;

class ContentTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new Content();
        $this->assertInstanceOf('\Charcoal\Object\Content', $obj);
    }

    public function testSetData()
    {
        $obj = new Content();
        $ret = $obj->set_data([
            'active'=>false,
            'position'=>42,
            'created'=>'2015-01-01 13:05:45',
            'created_by'=>'Me',
            'last_modified'=>'2015-04-01 22:10:30',
            'last_modified_by'=>'You'
        ]);
        $this->assertSame($ret, $obj);
        $this->assertNotTrue($obj->active());
        $this->assertEquals(42, $obj->position());
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->created());
        $this->assertEquals('Me', $obj->created_by());
        $expected = new DateTime('2015-04-01 22:10:30');
        $this->assertEquals($expected, $obj->last_modified());
        $this->assertEquals('You', $obj->last_modified_by());
    }

    public function testSetActive()
    {
        $obj = new Content();
        $this->assertTrue($obj->active());
        $ret = $obj->set_active(false);
        $this->assertSame($ret, $obj);
        $this->assertNotTrue($obj->active());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_active('foo');
    }

    public function testSetPosition()
    {
        $obj = new Content();
        $this->assertEquals(0, $obj->position());
        $ret = $obj->set_position(42);
        $this->assertSame($ret, $obj);
        $this->assertEquals(42, $obj->position());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_position('foo');
    }

    public function testSetCreated()
    {
        $obj = new Content();
        $ret = $obj->set_created('2015-01-01 13:05:45');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->created());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_created(false);
    }

    public function testSetCreatedBy()
    {
        $obj = new Content();
        $ret = $obj->set_created_by('Me');
        $this->assertSame($ret, $obj);
        $this->assertEquals('Me', $obj->created_by());

        //$this->setExpectedException('\InvalidArgumentException');
        //$obj->set_created_by(false);
    }

    public function testSetLastModified()
    {
        $obj = new Content();
        $ret = $obj->set_last_modified('2015-01-01 13:05:45');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->last_modified());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_last_modified(false);
    }

    public function testSetLastModifiedBy()
    {
        $obj = new Content();
        $ret = $obj->set_last_modified_by('Me');
        $this->assertSame($ret, $obj);
        $this->assertEquals('Me', $obj->last_modified_by());

        //$this->setExpectedException('\InvalidArgumentException');
        //$obj->set_last_modified_by(false);
    }

    public function testSetPreSave()
    {
        $obj = new Content();
        $this->assertSame(null, $obj->created());
        $this->assertSame(null, $obj->last_modified());

        $obj->pre_save();
        $this->assertNotSame(null, $obj->created());
        $this->assertNotSame(null, $obj->last_modified());

    }

    public function testSetPreUpdate()
    {
        $obj = new Content();
        $this->assertSame(null, $obj->last_modified());

        $obj->pre_update();
        $this->assertNotSame(null, $obj->last_modified());

    }
}

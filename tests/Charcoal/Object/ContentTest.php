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
            'created'=>'2015-01-01 13:05:45',
            'created_by'=>'Me',
            'last_modified'=>'2015-04-01 22:10:30',
            'last_modified_by'=>'You',

        ]);
        $this->assertSame($ret, $obj);
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->created());
        $this->assertEquals('Me', $obj->created_by());
        $expected = new DateTime('2015-04-01 22:10:30');
        $this->assertEquals($expected, $obj->last_modified());
        $this->assertEquals('You', $obj->last_modified_by());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_data(false);
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
}

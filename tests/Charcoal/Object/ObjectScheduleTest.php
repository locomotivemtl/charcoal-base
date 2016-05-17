<?php

use \Psr\Log\NullLogger;

use \Charcoal\Object\ObjectSchedule;

class ObjectScheduleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $metadataLoader = new \Charcoal\Model\MetadataLoader([
            'logger' => new \Psr\Log\NullLogger(),
            'base_path' => __DIR__,
            'paths' => ['metadata'],
            'config' => $GLOBALS['container']['config'],
            'cache'  => $GLOBALS['container']['cache']
        ]);

        $logger = new NullLogger();
        $this->obj = new ObjectSchedule([
            'logger'=>$logger,
            'metadata_loader' => $metadataLoader
        ]);
    }

    public function testSetObjType()
    {
        $this->assertNull($this->obj->objType());
        $ret = $this->obj->setObjType('foobar');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('foobar', $this->obj->objType());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setObjType(false);
    }

    public function testSetObjId()
    {
        $this->assertNull($this->obj->objId());
        $ret = $this->obj->setObjId(42);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(42, $this->obj->objId());
    }

    public function testSetPropertyIdent()
    {
        $this->assertNull($this->obj->propertyIdent());
        $ret = $this->obj->setPropertyIdent('foo');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('foo', $this->obj->propertyIdent());
    }

    public function testSetNewValue()
    {
        $this->assertNull($this->obj->newValue());
        $ret = $this->obj->setNewValue(42);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(42, $this->obj->newValue());
    }

    public function testSetProcessed()
    {
        $this->assertFalse($this->obj->processed());
        $ret = $this->obj->setProcessed(true);
        $this->assertSame($ret, $this->obj);
        $this->assertTrue($this->obj->processed());
    }

    public function testSetProcessingDate()
    {
        $obj = $this->obj;
        $this->assertNull($obj->processingDate());
        $ret = $obj->setProcessingDate('2015-01-01 13:05:45');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->processingDate());

        $obj->setProcessingDate(null);
        $this->assertNull($obj->processingDate());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setProcessingDate(false);
    }

    public function testSetProcessingDateInvalidTime()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setProcessingDate('A totally invalid date time');
    }

    public function testSetProcessedDate()
    {
        $obj = $this->obj;
        $this->assertNull($obj->processedDate());
        $ret = $obj->setProcessedDate('2015-01-01 13:05:45');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->processedDate());

        $obj->setProcessedDate(null);
        $this->assertNull($obj->processedDate());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setProcessedDate(false);
    }

    public function testSetProcessedDateInvalidTime()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setProcessedDate('A totally invalid date time');
    }

    public function testProcess()
    {
        $factory = new \Charcoal\Model\ModelFactory();
        $this->obj->setModelFactory($factory);

        $this->assertFalse($this->obj->process());

        $this->obj->setObjType('charcoal/object/content');
        $this->assertFalse($this->obj->process());

        $this->obj->setObjId(42);
        $this->assertFalse($this->obj->process());

        $this->obj->setPropertyIdent('foo');
        //q$this->obj->process();
    }
}

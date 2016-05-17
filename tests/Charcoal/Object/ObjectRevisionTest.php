<?php

use \Psr\Log\NullLogger;

use \Charcoal\Object\ObjectRevision;

class ObjectRevisionTest extends \PHPUnit_Framework_TestCase
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
        $this->obj = new ObjectRevision([
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

    public function testSetRevNum()
    {
        $this->assertNull($this->obj->revNum());
        $ret = $this->obj->setRevNum(66);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(66, $this->obj->revNum());

        $this->obj->setRevNum('42');
        $this->assertEquals(42, $this->obj->revNum());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setRevNum([]);
    }

    public function testSetRevTs()
    {
        $obj = $this->obj;
        $this->assertNull($obj->revTs());
        $ret = $obj->setRevTs('2015-01-01 13:05:45');
        $this->assertSame($ret, $obj);
        $expected = new DateTime('2015-01-01 13:05:45');
        $this->assertEquals($expected, $obj->revTs());

        $obj->setRevTs(null);
        $this->assertNull($obj->revTs());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setRevTs(false);
    }

    public function testSetRevUser()
    {
        $this->assertNull($this->obj->revUser());
        $ret = $this->obj->setRevUser('me');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('me', $this->obj->revUser());

        $this->obj->setRevUser(null);
        $this->assertNull($this->obj->revUser());

        $this->setExpectedException('\InvalidArgumentException');
        $this->obj->setRevUser(false);
    }

    public function testSetDataPrev()
    {
        $this->assertNull($this->obj->dataPrev());
        $ret = $this->obj->setDataPrev(['foo'=>1]);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(['foo'=>1], $this->obj->dataPrev());

        $this->assertEquals(['bar'], $this->obj->setDataPrev('["bar"]')->dataPrev());
        $this->assertEquals([], $this->obj->setDataPrev(null)->dataPrev());
    }

    public function testSetDataObj()
    {
        $this->assertNull($this->obj->dataObj());
        $ret = $this->obj->setDataObj(['foo'=>1]);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(['foo'=>1], $this->obj->dataObj());

        $this->assertEquals(['bar'], $this->obj->setDataObj('["bar"]')->dataObj());
        $this->assertEquals([], $this->obj->setDataObj(null)->dataObj());
    }

    public function testSetDataDiff()
    {
        $this->assertNull($this->obj->dataDiff());
        $ret = $this->obj->setDataDiff(['foo'=>1]);
        $this->assertSame($ret, $this->obj);
        $this->assertEquals(['foo'=>1], $this->obj->dataDiff());

        $this->assertEquals(['bar'], $this->obj->setDataDiff('["bar"]')->dataDiff());
        $this->assertEquals([], $this->obj->setDataDiff(null)->dataDiff());
    }

    public function testCreateDiff()
    {
        $this->assertEquals([], $this->obj->createDiff([], []));
        $ret = $this->obj->createDiff(['foo'=>1], ['foo'=>2]);
        $this->assertEquals([['foo'=>1],['foo'=>2]], $ret);

        $ret = $this->obj->createDiff(['foo'=>1], ['foo'=>1]);
        $this->assertEquals([], $ret);


        $this->obj->setDataPrev(['foo'=>1, 'bar'=>1, 'baz'=>1]);
        $this->obj->setDataObj(['foo'=>1, 'bar'=>42]);
        $ret = $this->obj->createDiff();

        $this->assertEquals([['bar'=>1, 'baz'=>1], ['bar'=>42]], $ret);
    }
}

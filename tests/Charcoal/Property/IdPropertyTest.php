<?php

namespace Charcoal\Tests\Property;

use \Charcoal\Property\IdProperty as IdProperty;

/**
* ## TODOs
* - 2015-03-12:
*/
class IdPropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new IdProperty();
        $this->assertInstanceOf('\Charcoal\Property\IdProperty', $obj);
    }

    public function testSetData()
    {
        $obj = new IdProperty();
        $ret = $obj->set_data([
            'mode'=>'uniqid'
        ]);
        $this->assertSame($ret, $obj);
        $this->assertEquals('uniqid', $obj->mode());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_data(false);
    }

    public function testSetMode()
    {
        $obj = new IdProperty();
        $this->assertEquals('auto-increment', $obj->mode());

        $ret = $obj->set_mode('uuid');
        $this->assertSame($ret, $obj);
        $this->assertEquals('uuid', $obj->mode());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_mode('foo');
    }

    public function testSaveAndAutoGenerate()
    {
        $obj = new IdProperty();
        $obj->set_mode('auto-increment');
        $id = $obj->save();
        $this->assertEquals($id, $obj->val());
        $this->assertEquals('', $obj->val());

        $obj = new IdProperty();
        $obj->set_mode('uniqid');
        $id = $obj->save();
        $this->assertEquals($id, $obj->val());
        $this->assertEquals(13, strlen($obj->val()));

        $obj = new IdProperty();
        $obj->set_mode('uuid');
        $id = $obj->save();
        $this->assertEquals($id, $obj->val());
        $this->assertEquals(36, strlen($obj->val()));

    }
}

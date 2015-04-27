<?php

namespace Charcoal\Tests\Property;

use \Charcoal\Property\BooleanProperty as BooleanProperty;

/**
* ## TODOs
* - 2015-03-12:
*/
class BooleanPropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new BooleanProperty();
        $this->assertInstanceOf('\Charcoal\Property\BooleanProperty', $obj);
    }

    public function testSetData()
    {
        $obj = new BooleanProperty();
        $data = [
            'true_label'=>'foo',
            'false_label'=>'bar'
        ];
        $ret = $obj->set_data($data);

        $this->assertSame($ret, $obj);

        $this->assertEquals('foo', $obj->true_label());
        $this->assertEquals('bar', $obj->false_label());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_data(false);
    }

    public function testSetTrueLabel()
    {
        $obj = new BooleanProperty();
        $ret = $obj->set_true_label('foo');
        $this->assertSame($ret, $obj);

        $this->assertEquals('foo', $obj->true_label());

        //$this->setExpectedException('\InvalidArgumentException');
        //$obj->set_true_label(false);
    }

    public function testSetFalseLabel()
    {
        $obj = new BooleanProperty();
        $ret = $obj->set_false_label('foo');
        $this->assertSame($ret, $obj);

        $this->assertEquals('foo', $obj->false_label());

        //$this->setExpectedException('\InvalidArgumentException');
        //$obj->set_false_label(false);
    }

    public function testSqlType()
    {
        $obj = new BooleanProperty();
        $this->assertEquals('TINYINT(1) UNSIGNED', $obj->sql_type());
    }
}

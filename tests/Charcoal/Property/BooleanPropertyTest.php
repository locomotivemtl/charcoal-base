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

    public function testType()
    {
        $obj = new BooleanProperty();
        $this->assertEquals('boolean', $obj->type());
    }

    public function testDisplayVal()
    {
        $obj = new BooleanProperty();
        $this->assertEquals('False', $obj->display_val());
        $obj->set_val(true);
        $this->assertEquals('True', $obj->display_val());

        $obj->set_true_label('Oui');
        $obj->set_false_label('Non');

        $this->assertEquals('Oui', $obj->display_val(true));
        $this->assertEquals('Non', $obj->display_val(false));


    }

    /**
    * Assert that the `set_multiple()` method:
    * - set the multiple to false, if false or falsish value
    * - throws exception otherwise (truthish or invalid value)
    * - is chainable
    */
    public function testSetMultiple()
    {
        $obj = new BooleanProperty();
        $ret = $obj->set_multiple(0);
        $this->assertSame($ret, $obj);
        $this->assertSame(false, $ret->multiple());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_multiple(1);
    }

    public function testMultiple()
    {
        $obj = new BooleanProperty();
        $this->assertSame(false, $obj->multiple());
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

    public function testSqlExtra()
    {
        $obj = new BooleanProperty();
        $this->assertSame('', $obj->sql_extra());
    }

    public function testSqlType()
    {
        $obj = new BooleanProperty();
        $this->assertEquals('TINYINT(1) UNSIGNED', $obj->sql_type());
    }

    public function testSqlPdoType()
    {
        $obj = new BooleanProperty();
        $this->assertEquals(\PDO::PARAM_BOOL, $obj->sql_pdo_type());
    }

    public function testChoices()
    {
        $obj = new BooleanProperty();
        $obj->set_val(false);
        $choices = [
            [
                'label'=>'True',
                'selected'=>false,
                'value'=>1
            ],
            [
                'label'=>'False',
                'selected'=>true,
                'value'=>0
            ]
        ];
        $this->assertEquals($choices, $obj->choices());
        
    }

    public function testSave()
    {
        $obj = new BooleanProperty();
        
        $obj->set_val(true);
        $this->assertTrue($obj->save());

        $obj->set_val(false);
        $this->assertNotTrue($obj->save()); 

    }
}

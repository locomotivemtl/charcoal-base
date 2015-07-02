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
        $ret = $obj->set_data(
            [
            'mode'=>'uniqid'
            ]
        );
        $this->assertSame($ret, $obj);
        $this->assertEquals('uniqid', $obj->mode());
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

    public function testSqlExtra()
    {
        $obj = new IdProperty();
        $obj->set_mode('auto-increment');
        $ret = $obj->sql_extra();
        $this->assertEquals('AUTO_INCREMENT', $ret);

        $obj->set_mode('uniqid');
        $ret = $obj->sql_extra();
        $this->assertEquals('', $ret);
    }

    public function testSqlType()
    {
        $obj = new IdProperty();
        $obj->set_mode('auto-increment');
        $ret = $obj->sql_type();
        $this->assertEquals('INT(10) UNSIGNED', $ret);

        $obj->set_mode('uniqid');
        $ret = $obj->sql_type();
        $this->assertEquals('CHAR(13)', $ret);

        $obj->set_mode('uuid');
        $ret = $obj->sql_type();
        $this->assertEquals('CHAR(36)', $ret);
    }

    public function testSqlPdoType()
    {
        $obj = new IdProperty();
        $obj->set_mode('auto-increment');
        $ret = $obj->sql_pdo_type();
        $this->assertEquals(\PDO::PARAM_INT, $ret);

        $obj->set_mode('uniqid');
        $ret = $obj->sql_pdo_type();
        $this->assertEquals(\PDO::PARAM_STR, $ret);

        $obj->set_mode('uuid');
        $ret = $obj->sql_pdo_type();
        $this->assertEquals(\PDO::PARAM_STR, $ret);
    }
}

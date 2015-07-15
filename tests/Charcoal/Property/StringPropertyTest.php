<?php

namespace Charcoal\Tests\Property;

use \Charcoal\Property\StringProperty as StringProperty;

/**
* ## TODOs
* - 2015-03-12:
*/
class StringPropertyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        mb_internal_encoding('UTF-8');
    }

    /**
    * Hello world
    */
    public function testConstructor()
    {
        $obj = new StringProperty();
        $this->assertInstanceOf('\Charcoal\Property\StringProperty', $obj);

        $this->assertEquals(0, $obj->min_length());
        $this->assertEquals(255, $obj->max_length());
        $this->assertEquals('', $obj->regexp());

    }

    public function testSetData()
    {
        $obj = new StringProperty();
        $data = [
            'min_length'=>5,
            'max_length'=>42,
            'regexp'=>'/[0-9]*/',
            'allow_empty'=>false
        ];
        $ret = $obj->set_data($data);

        $this->assertSame($ret, $obj);

        $this->assertEquals(5, $obj->min_length());
        $this->assertEquals(42, $obj->max_length());
        $this->assertEquals('/[0-9]*/', $obj->regexp());
        $this->assertEquals(false, $obj->allow_empty());
    }

    public function testSetMinLength()
    {
        $obj = new StringProperty();

        $ret = $obj->set_min_length(5);
        $this->assertSame($ret, $obj);
        $this->assertEquals(5, $obj->min_length());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_min_length('foo');
    }

    public function testSetMinLenghtNegative()
    {
        $obj = new StringProperty();
        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_min_length(-1);
    }
    
    public function testSetMaxLength()
    {
        $obj = new StringProperty();

        $ret = $obj->set_max_length(5);
        $this->assertSame($ret, $obj);
        $this->assertEquals(5, $obj->max_length());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_max_length('foo');
    }

    public function testSetMaxLenghtNegative()
    {
        $obj = new StringProperty();
        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_max_length(-1);
    }

    public function testSetRegexp()
    {
        $obj = new StringProperty();

        $ret = $obj->set_regexp('[a-z]');
        $this->assertSame($ret, $obj);
        $this->assertEquals('[a-z]', $obj->regexp());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_regexp(null);
    }

    public function testSetAllowempty()
    {
        $obj = new StringProperty();
        $this->assertEquals(true, $obj->allow_empty());

        $ret = $obj->set_allow_empty(false);
        $this->assertSame($ret, $obj);
        $this->assertEquals(false, $obj->allow_empty());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_allow_empty('foo');
    }

    public function testLength()
    {
        $obj = new StringProperty();

        $obj->set_val('');
        $this->assertEquals(0, $obj->length());

        $obj->set_val('a');
        $this->assertEquals(1, $obj->length());

        $obj->set_val('foo');
        $this->assertEquals(3, $obj->length());

        $obj->set_val('é');
        //$this->assertEquals(1, $obj->length());
    }

    public function testLengthWitoutValThrowsException()
    {
        $this->setExpectedException('\Exception');
        $obj = new StringProperty();
        $obj->length();
    }

    public function testValidateMinLength()
    {
        $obj = new StringProperty();
        $obj->set_min_length(5);
        $obj->set_val('1234');
        $this->assertNotTrue($obj->validate_min_length());

        $obj->set_val('12345');
        $this->assertTrue($obj->validate_min_length());

        $obj->set_val('123456789');
        $this->assertTrue($obj->validate_min_length());
    }

    public function testValidateMinLengthUTF8()
    {
        $obj = new StringProperty();
        $obj->set_min_length(5);

        $obj->set_val('Éçä˚');
        $this->assertNotTrue($obj->validate_min_length());

        $obj->set_val('∂çäÇµ');
        $this->assertTrue($obj->validate_min_length());

        $obj->set_val('ß¨ˆ®©˜ßG');
        $this->assertTrue($obj->validate_min_length());
    }

    public function testValidateMinLengthAllowEmpty()
    {
        $obj = new StringProperty();
        $obj->set_min_length(5);
        $obj->set_val('');

        $obj->set_allow_empty(true);
        $this->assertTrue($obj->validate_min_length());

        $obj->set_allow_empty(false);
        $this->assertNotTrue($obj->validate_min_length());
    }

    public function testValidateMinLengthWithoutValReturnsFalse()
    {
        $obj = new StringProperty();
        $obj->set_min_length(5);

        $this->assertNotTrue($obj->validate_min_length());
    }

    public function testValidateMinLengthWithoutMinLengthReturnsTrue()
    {
        $obj = new StringProperty();

        $this->assertTrue($obj->validate_min_length());

        $obj->set_val('1234');
        $this->assertTrue($obj->validate_min_length());
    }

    public function testValidateMaxLength()
    {
        $obj = new StringProperty();
        $obj->set_max_length(5);
        $obj->set_val('1234');
        $this->assertTrue($obj->validate_max_length());

        $obj->set_val('12345');
        $this->assertTrue($obj->validate_max_length());

        $obj->set_val('123456789');
        $this->assertNotTrue($obj->validate_max_length());
    }

    public function testValidateMaxLengthUTF8()
    {
        $obj = new StringProperty();
        $obj->set_max_length(5);

        $obj->set_val('Éçä˚');
        $this->assertTrue($obj->validate_max_length());

        $obj->set_val('∂çäÇµ');
        $this->assertTrue($obj->validate_max_length());

        $obj->set_val('ß¨ˆ®©˜ßG');
        $this->assertNotTrue($obj->validate_max_length());
    }

    /*public function testValidateMaxLengthWithoutValReturnsFalse()
	{
		$obj = new StringProperty();
		$obj->set_max_length(5);

		$this->assertNotTrue($obj->validate_max_length());
	}*/

    public function testValidateMaxLengthWithZeroMaxLengthReturnsTrue()
    {
        $obj = new StringProperty();
        $obj->set_max_length(0);

        $this->assertTrue($obj->validate_max_length());

        $obj->set_val('1234');
        $this->assertTrue($obj->validate_max_length());
    }


    public function testValidateRegexp()
    {
        $obj = new StringProperty();
        $obj->set_regexp('/[0-9*]/');

        $obj->set_val('123');
        $this->assertTrue($obj->validate_regexp());

        $obj->set_val('abc');
        $this->assertNotTrue($obj->validate_regexp());
    }

    public function testValidateRegexpEmptyRegexpReturnsTrue()
    {
        $obj = new StringProperty();
        $this->assertTrue($obj->validate_regexp());

        $obj->set_val('123');
        $this->assertTrue($obj->validate_regexp());
    }

    public function testSqlType()
    {
        $obj = new StringProperty();
        $this->assertEquals('VARCHAR(255)', $obj->sql_type());

        $obj->set_max_length(20);
        $this->assertEquals('VARCHAR(20)', $obj->sql_type());

        $obj->set_max_length(256);
        $this->assertEquals('TEXT', $obj->sql_type());
    }

    public function testSqlTypeMultiple()
    {
        $obj = new StringProperty();
        $this->assertEquals('VARCHAR(255)', $obj->sql_type());

        $obj->set_multiple(true);
        $this->assertEquals('TEXT', $obj->sql_type());
    }

    public function testSqlPdoType()
    {
        $obj = new StringProperty();
        $this->assertEquals(\PDO::PARAM_STR, $obj->sql_pdo_type());
    }
}

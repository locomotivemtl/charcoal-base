<?php

namespace Charcoal\Tests\Property;

use \DateTime;

use \Charcoal\Property\DatetimeProperty;

/**
 * ## TODOs
 * - 2015-03-12:
 */
class DatetimePropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Assert that the `type` method:
     * - returns "datetime"
     */
    public function testType()
    {
        $obj = new DatetimeProperty();
        $this->assertEquals('datetime', $obj->type());
    }

    /**
     * Assert that the `set_data` method:
     * - is chainable
     * - sets the data
     */
    public function testSetData()
    {
        $obj = new DatetimeProperty();

        $ret = $obj->set_data([
            'min'=>'2015-01-01 00:00:00',
            'max'=>'2025-01-01 00:00:00',
            'format'=>'Y.m.d'
        ]);

        $this->assertSame($ret, $obj);

        $this->assertEquals(new DateTime('2015-01-01 00:00:00'), $obj->min());
        $this->assertEquals(new DateTime('2025-01-01 00:00:00'), $obj->max());
        $this->assertEquals('Y.m.d', $obj->format());
    }

    /**
     * Assert that calling `set_val` with a null parameters:
     * - Is chainable
     * - Set the value to null if "allow_null" is true
     * - Throw an exception if "allow_null" is false
     */
    public function testSetValWithNullValue()
    {
        $obj = new DatetimeProperty();
        $obj->set_allow_null(true);

        $ret = $obj->set_val(null);
        $this->assertSame($ret, $obj);
        $this->assertEquals(null, $obj->val());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_allow_null(false);
        $obj->set_val(null);
    }

    /**
     * Assert that the `set_val` method:
     * - Is chainable
     * - Sets the value when the parameter is a string or a DateTime object
     * - Throws an exception otherwise
     */
    public function testSetVal()
    {
        $obj = new DatetimeProperty();
        $ret = $obj->set_val('2000-01-01 00:00:00');
        $this->assertSame($ret, $obj);
        $this->assertEquals(new DateTime('2000-01-01 00:00:00'), $obj->val());

        $dt = new Datetime('October 1st, 1984');
        $ret = $obj->set_val($dt);
        $this->assertSame($ret, $obj);
        $this->assertEquals($dt, $obj->val());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_val([]);
    }

    public function testStorageVal()
    {
        $obj = new DatetimeProperty();

        $obj->set_val('October 1st, 1984');
        $this->assertEquals('1984-10-01 00:00:00', $obj->storage_val());

        $obj->set_val(null);

        $obj->set_allow_null(true);
        $this->assertEquals(null, $obj->storage_val());

        $obj->set_allow_null(false);
        $this->setExpectedException('\Exception');
        $obj->storage_val();
    }

    public function testDisplayVal()
    {
        $obj = new DatetimeProperty();
        $this->assertEquals('', $obj->display_val());

        $obj->set_val('October 1st, 2015 15:00:00');
        $this->assertEquals('2015-10-01 15:00:00', $obj->display_val());

        $obj->set_format('Y/m/d');
        $this->assertEquals('2015/09/01', $obj->display_val('September 1st, 2015'));
    }

    /**
     * Assert that the `set_multiple()` method:
     * - set the multiple to false, if false or falsish value
     * - throws exception otherwise (truthish or invalid value)
     * - is chainable
     */
    public function testSetMultiple()
    {
        $obj = new DatetimeProperty();
        $ret = $obj->set_multiple(0);
        $this->assertSame($ret, $obj);
        $this->assertSame(false, $ret->multiple());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_multiple(1);
    }

    public function testMultiple()
    {
        $obj = new DatetimeProperty();
        $this->assertSame(false, $obj->multiple());
    }

    /**
     * Assert that the `min` method:
     * - is chainable
     * - sets the min value from a string or DateTime object
     * - throws exception when the argument is invalid
     * -
     */
    public function testSetMin()
    {
        $obj = new DatetimeProperty();

        // Setting by string
        $ret = $obj->set_min('2020-01-01 01:02:03');
        $this->assertSame($ret, $obj);
        $this->assertEquals(new Datetime('2020-01-01 01:02:03'), $obj->min());

        // Setting by Datetime
        $dt = new DateTime('today');
        $ret = $obj->set_min($dt);
        $this->assertSame($ret, $obj);
        $this->assertEquals($dt, $obj->min());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_min('foo');

        // Ensure setting a null value works
        $obj->set_min(null);
        $this->assertEquals(null, $obj->min());

    }

    /**
     * Assert that the `max` method:
     * - is chainable
     * - sets the max value
     * - throws exception when the argument is invalid
     */
    public function testSetMax()
    {
        $obj = new DatetimeProperty();

        $ret = $obj->set_max('2020-01-01 01:02:03');
        $this->assertSame($ret, $obj);
        $this->assertEquals(new Datetime('2020-01-01 01:02:03'), $obj->max());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_max('foo');

        // Ensure setting a null value works
        $obj->set_max(null);
        $this->assertEquals(null, $obj->max());
    }

    /**
     * Assert that the `format()` method
     * - is chainable
     * and that the `set_format()`:
     * - is chainable
     * - sets the format
     * - throws an exception if not a string or null
     */
    public function testSetFormat()
    {
        $obj = new DatetimeProperty();
        $this->assertEquals('Y-m-d H:i:s', $obj->format());

        $ret = $obj->set_format('Y/m/d');
        $this->assertSame($ret, $obj);
        $this->assertEquals('Y/m/d', $obj->format());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_format(null);
    }

    public function testSave()
    {
        $obj = new DatetimeProperty();
        $this->assertEquals(null, $obj->save());

        $obj->set_val('2015-01-01');
        $this->assertEquals(new DateTime('2015-01-01'), $obj->save());

    }

    /**
     * Assert that the `validate_min` method:
     * - Returns true if no "min" is set
     * - Returns true when the value is equal or bigger
     * - Returns false when the value is smaller
     */
    public function testValidateMin()
    {
        $obj = new DatetimeProperty();
        $this->assertTrue($obj->validate_min());

        $obj->set_min('2015-01-01');

        // Equal
        $obj->set_val('2015-01-01');
        $this->assertTrue($obj->validate_min());

        // Bigger
        $obj->set_val('2016-01-01');
        $this->assertTrue($obj->validate_min());

        // Smaller
        $obj->set_val('2014-01-01');
        $this->assertNotTrue($obj->validate_min());

    }

    /**
     * Assert that the `validate_max` method:
     * - Returns true if no "max" is set
     * - Returns true when the value is equal or smaller
     * - Returns false when the value is bigger
     */
    public function testValidateMax()
    {
        $obj = new DatetimeProperty();
        $this->assertTrue($obj->validate_max());

        $obj->set_max('2015-01-01');

        // Equal
        $obj->set_val('2015-01-01');
        $this->assertTrue($obj->validate_max());

        // Smaller
        $obj->set_val('2014-01-01');
        $this->assertTrue($obj->validate_max());

        // Bigger
        $obj->set_val('2016-01-01');
        $this->assertNotTrue($obj->validate_max());
    }

    public function testSqlExtra()
    {
        $obj = new DatetimeProperty();
        $this->assertSame('', $obj->sql_extra());
    }

    public function testSqlType()
    {
        $obj = new DateTimeProperty();
        $this->assertEquals('DATETIME', $obj->sql_type());
    }

    public function testSqlPdoType()
    {
        $obj = new DatetimeProperty();
        $this->assertEquals(\PDO::PARAM_STR, $obj->sql_pdo_type());
    }
}

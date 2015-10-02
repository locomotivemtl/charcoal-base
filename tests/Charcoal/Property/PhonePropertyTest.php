<?php

namespace Charcoal\Tests\Property;

use \Charcoal\Property\PhoneProperty;

/**
*
*/
class PhonePropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
    * Hello world
    */
    public function testDefaultValues()
    {
        $obj = new PhoneProperty();
        $this->assertInstanceOf('\Charcoal\Property\PhoneProperty', $obj);

        $this->assertEquals(0, $obj->min_length());
        $this->assertEquals(16, $obj->max_length());
    }

    public function testType()
    {
        $obj = new PhoneProperty();
        $this->assertEquals('phone', $obj->type());
    }

    public function testSanitize()
    {
        $obj = new PhoneProperty();

        $obj->set_val('514.555.9999');
        $this->assertEquals('5145559999', $obj->sanitize());

        $this->assertEquals('5145551234', $obj->sanitize('(514) 555-1234'));

    }

    public function testDisplayVal()
    {
        $obj = new PhoneProperty();

        $obj->set_val('5145559999');
        $this->assertEquals('(514) 555-9999', $obj->display_val());

        $this->assertEquals('(514) 555-1234', $obj->display_val('5145551234'));

        $this->assertEquals('(514) 555-1234', $obj->display_val('514-555-1234'));
    }
}

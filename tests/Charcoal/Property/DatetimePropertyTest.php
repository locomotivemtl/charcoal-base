<?php

namespace Charcoal\Tests\Property;

use \Charcoal\Property\DatetimeProperty as DatetimeProperty;

/**
* ## TODOs
* - 2015-03-12:
*/
class DatetimePropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $obj = new DatetimeProperty();
        $this->assertEquals('datetime', $obj->type());
    }
}

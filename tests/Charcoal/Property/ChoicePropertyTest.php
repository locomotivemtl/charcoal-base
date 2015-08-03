<?php

namespace Charcoal\Tests\Property;

use \Charcoal\Property\ChoiceProperty as ChoiceProperty;

/**
* ## TODOs
* - 2015-03-12:
*/
class ChoicePropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $obj = new ChoiceProperty();
        $this->assertEquals('choice', $obj->type());
    }
}

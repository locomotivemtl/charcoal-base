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
}

<?php

namespace Charcoals\Tests\Email;

use \Charcoal\Email\Email as Email;

/**
* Test the AbstractEmail methods, through concrete `Email` class.
*/
class AbstractEmailTest extends \PHPUnit_Framework_Testcase
{
    public function testConstructor()
    {
        $obj = new Email();
        $this->assertInstanceOf('Charcoal\Email\Email', $obj);
    }

    public function testSetCampaign()
    {
        $obj = new Email();
        $ret = $obj->set_campaign('foo');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->campaign());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_campaign([1, 2, 3]);

    }
}

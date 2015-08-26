<?php

namespace Charcoals\Tests\Action;

use \Charcoal\Action\Action as Action;

/**
* Test the AbstractAction methods, through concrete `Action` class.
*/
class AbstractActionTest extends \PHPUnit_Framework_Testcase
{

    private $obj;

    public function setUp()
    {
        $this->obj = $this->getMockForAbstractClass('\Charcoal\Action\AbstractAction');
    }

    public function testSetMode()
    {
        $obj = $this->obj;
        $this->assertEquals('redirect', $obj->mode());

        $ret = $obj->set_mode('json');

        $this->assertSame($ret, $obj);
        $this->assertEquals('json', $obj->mode());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_mode([1, 2, 3]);
    }


    public function testSetSuccess()
    {
        $obj = $this->obj;
        $this->assertEquals(false, $obj->success());

        $ret = $obj->set_success(true);

        $this->assertSame($ret, $obj);
        $this->assertEquals(true, $obj->success());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_success('foo');
    }
}

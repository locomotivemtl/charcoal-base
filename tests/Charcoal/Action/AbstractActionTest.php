<?php

namespace Charcoals\Tests\Action;

use \Charcoal\Action\Action as Action;

/**
* Test the AbstractAction methods, through concrete `Action` class.
*/
class AbstractActionTest extends \PHPUnit_Framework_Testcase
{
    public function testConstructor()
    {
        $obj = new Action();
        $this->assertInstanceOf('Charcoal\Action\Action', $obj);
    }

    public function testSetMode()
    {
        $obj = new Action();
        $this->assertEquals('redirect', $obj->mode());

        $ret = $obj->set_mode('json');

        $this->assertSame($ret, $obj);
        $this->assertEquals('json', $obj->mode());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_mode([1, 2, 3]);
    }


    public function testSetSuccess()
    {
        $obj = new Action();
        $this->assertEquals(false, $obj->success());

        $ret = $obj->set_success(true);

        $this->assertSame($ret, $obj);
        $this->assertEquals(true, $obj->success());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_success('foo');
    }

    public function testSetOutput()
    {
        $obj = new Action();
        ob_start();
        $obj->output();
        $ret = ob_get_clean();

        ob_start();
        $obj->set_mode('json');
        $obj->output();
        $ret = ob_get_clean();
        
    }
}

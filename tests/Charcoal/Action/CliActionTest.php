<?php

namespace Charcoals\Tests\Action;

use \Charcoal\Action\CliAction as CliAction;

/**
* Test the CliAction class AND the CliActionTrait it uses
*/
class CliActionTest extends \PHPUnit_Framework_Testcase
{


    static public function setUpBeforeClass()
    {
        include_once 'CliActionClass.php';
    }
    
    public function testConstructor()
    {
        $obj = new CliActionClass();
        $this->assertInstanceOf('Charcoal\Action\CliAction', $obj);
    }

    public function testSetCliData()
    {
        $obj = new CliActionClass();
        $ret = $obj->set_data([
            'ident'=>'foo',
            'description'=>'bar',
            'arguments'=>[]
        ]);
        $this->assertSame($ret, $obj);

        $this->assertEquals('foo', $obj->ident());
        $this->assertEquals('bar', $obj->description());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_data(false);
    }

    public function testSetIdent()
    {
        $obj = new CliActionClass();
        $ret = $obj->set_ident('foo');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->ident());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_ident(false);
    }

    public function testSetDescription()
    {
        $obj = new CliActionClass();
        $ret = $obj->set_description('foo');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->description());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_description(false);
    }
}

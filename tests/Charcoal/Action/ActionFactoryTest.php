<?php

namespace Charcoals\Tests\Action;

use \Charcoal\Action\ActionFactory as ActionFactory;

class ActionFactoryTest extends \PHPUnit_Framework_Testcase
{
    public function testConstructor()
    {
        $obj = ActionFactory::instance();
        $this->assertInstanceOf('Charcoal\Action\ActionFactory', $obj);
    }
}

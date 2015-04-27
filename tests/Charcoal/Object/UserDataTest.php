<?php

namespace Charcoal\Tests\Object;

use \Charcoal\Object\UserData as UserData;

class UserDataTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new UserData();
        $this->assertInstanceOf('\Charcoal\Object\UserData', $obj);
    }
}

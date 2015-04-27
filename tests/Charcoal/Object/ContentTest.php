<?php

namespace Charcoal\Tests\Object;

use \Charcoal\Object\Content as Content;

class ContentTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new Content();
        $this->assertInstanceOf('\Charcoal\Object\Content', $obj);
    }
}

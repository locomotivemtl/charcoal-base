<?php

namespace Charcoal\Tests\Object;

use \DateTime;

use \Charcoal\Object\ExpirableTrait;

/**
 *
 */
class ExpirableTraitTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    /**
     * Create mock object from trait.
     */
    public function setUp()
    {
        $this->obj = $this->getMockForTrait('\Charcoal\Object\ExpirableTrait');
    }

    /**
     * Assert that the `setExpiredOn` method:
     * - is chainable
     * - sets the expiredOn value when a string is passed
     * - sets the expiredOn value when a DateTime is passed
     * - throws an InvalidArgumentException if other types of arguments are passed
     */
    public function testSetExpiredOn()
    {
        $obj = $this->obj;
        $dt = new DateTime('2015-01-01 00:00:00');

        $ret = $obj->setExpiredOn('2015-01-01 00:00:00');
        $this->assertSame($ret, $obj);
        $this->assertEquals($dt, $obj->expiredOn());

        $obj->setExpiredOn($dt);
        $this->assertEquals($dt, $obj->expiredOn());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setExpiredOn('foobar');
    }

    public function testIsExpired()
    {
        $obj = $this->obj;
        $obj->setExpiredOn('yesterday');
        $this->assertTrue($obj->isExpired());
    }
}

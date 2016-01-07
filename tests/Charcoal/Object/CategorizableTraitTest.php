<?php

namespace Charcoal\Tests\Object;

/**
 *
 */
class CategorizableTraitTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    /**
     * Create mock object from trait.
     */
    public function setUp()
    {
        $this->obj = $this->getMockForTrait('\Charcoal\Object\CategorizableTrait');
    }

    public function testSetCategoryType()
    {
        $obj = $this->obj;
        $this->assertNull($obj->category_type());

        $ret = $obj->set_category_type('foobar');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foobar', $obj->category_type());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_category_type(false);

    }
}

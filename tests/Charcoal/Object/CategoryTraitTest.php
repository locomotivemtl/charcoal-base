<?php

namespace Charcoal\Tests\Object;

/**
 *
 */
class CategoryTraitTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    /**
     * Create mock object from trait.
     */
    public function setUp()
    {
        $this->obj = $this->getMockForTrait('\Charcoal\Object\CategoryTrait');
    }

    public function testSetCategoryItemType()
    {
        $obj = $this->obj;

        $ret = $obj->set_category_item_type('foobar');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foobar', $obj->category_item_type());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_category_item_type(false);

    }
}

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

        $ret = $obj->setCategoryItemType('foobar');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foobar', $obj->categoryItemType());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setCategoryItemType(false);

    }
}

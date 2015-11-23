<?php

namespace Charcoal\Tests\Object;

use \DateTime;

use \Charcoal\Translation\TranslationString;

;

/**
*
*/
class RoutableTraitTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    /**
    * Create mock object from trait.
    */
    public function setUp()
    {
        $this->obj = $this->getMockForTrait('\Charcoal\Object\RoutableTrait');
    }

    /**
    * Assert that the `set_routable` method:
    * - is chainable
    * - sets the routable value (and cast to bool)
    * And that the `routable()` method:
    * - defaults to true
    */
    public function testSetRoutable()
    {
        $obj = $this->obj;
        $this->assertTrue($obj->routable());

        $ret = $obj->set_routable(false);
        $this->assertSame($ret, $obj);
        $this->assertFalse($obj->routable());
    }

    public function testSetSlugPattern()
    {
        $obj = $this->obj;
        $this->assertEquals('', $obj->slug_pattern());

        $ret = $obj->set_slug_pattern('hello');
        $this->assertSame($ret, $obj);
        $this->assertEquals(new TranslationString('hello'), $obj->slug_pattern());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_slug_pattern(false);
    }

    public function testSetSlug()
    {
        $obj = $this->obj;
        $this->assertEquals('', $obj->slug());

        $ret = $obj->set_slug('foobar');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foobar', $obj->slug());
    }

    public function testSlugPatternGeneratesSlug()
    {
        $obj = $this->obj;
        $obj->set_slug_pattern('foobar');

        $this->assertEquals('foobar', $obj->generate_slug());
    }
}

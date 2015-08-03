<?php

namespace Charcoal\Tests\Object;

use \DateTime as DateTime;

use \Charcoal\Object\Content as Content;
use \Charcoal\Tests\Object\HierarchicalClass as Hierarchy;

class HierarchicalTraitTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    public function setUp()
    {
        include_once 'HierarchicalClass.php';
        $this->obj = new Hierarchy();
    }

    public function testSetHierarchicalData()
    {
        $obj = $this->obj;
        $ret = $obj->set_hierarchical_data(
            [

            ]
        );
        $this->assertSame($ret, $obj);
    }

    public function testSetMaster()
    {
        $obj = $this->obj;
        $master = new Hierarchy();
        $ret = $obj->set_master($master);
        $this->assertSame($ret, $obj);
        $this->assertSame($master, $obj->master());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_master(['foobar']);
    }

    public function testHasMaster()
    {
        $obj = $this->obj;
        $this->assertFalse($obj->has_master());

        $master = new Hierarchy();
        $obj->set_master($master);
        $this->assertTrue($obj->has_master());

        $this->assertFalse($master->has_master());
    }

    public function testIsTopLevel()
    {
        $obj = $this->obj;
        $this->assertTrue($obj->is_top_level());

        $master = new Hierarchy();
        $obj->set_master($master);
        $this->assertFalse($obj->is_top_level());
    }

    public function testIsLastLevel()
    {
        $obj = $this->obj;
        $this->assertTrue($obj->is_last_level());

        $children = array_fill(0, 4, (new Hierarchy()));
        $obj->set_children($children);
        $this->assertFalse($obj->is_last_level());
    }

    public function testHierarchyLevel()
    {
        $obj = $this->obj;
        $this->assertEquals(1, $obj->hierarchy_level());

        $master = new Hierarchy();
        $children = array_fill(0, 4, (new Hierarchy()));
        $obj->set_master($master);
        $obj->set_children($children);
        $this->assertEquals(2, $obj->hierarchy_level());

        $master2 = new Hierarchy();
        $obj->master()->set_master($master2);

        $this->assertEquals(3, $obj->hierarchy_level());
    }

    public function testToplevelMaster()
    {
        $obj = $this->obj;
        $this->assertSame(null, $obj->toplevel_master());

        $master1 = new Hierarchy();
        $master2 = new Hierarchy();

        $obj->set_master($master1);
        $this->assertSame($master1, $obj->toplevel_master());

        $master1->set_master($master2);
        $this->assertSame($master2, $obj->toplevel_master());
    }

    public function testHierarchy()
    {
        $obj = $this->obj;
        $this->assertEquals([], $obj->hierarchy());

        $master1 = new Hierarchy();
        $master2 = new Hierarchy();

        $obj->set_master($master1);
        $this->assertSame([$master1], $obj->hierarchy());

        $master1->set_master($master2);
        $this->assertSame([$master1, $master2], $obj->hierarchy());
    }

    public function testInvertedHierarchy()
    {
        $obj = $this->obj;
        $this->assertEquals([], $obj->inverted_hierarchy());

        $master1 = new Hierarchy();
        $master2 = new Hierarchy();

        $obj->set_master($master1);
        $this->assertSame([$master1], $obj->inverted_hierarchy());

        $master1->set_master($master2);
        $this->assertSame([$master2, $master1], $obj->inverted_hierarchy());
    }

    public function testIsMasterOf()
    {
        $obj = $this->obj;
        $master = new Hierarchy();

        $this->assertFalse($master->is_master_of($obj));
        $obj->set_master($master);
        $this->assertTrue($master->is_master_of($obj));
        $this->assertFalse($obj->is_master_of($master));
    }

    public function testHasChildren()
    {
        $obj = $this->obj;
        $this->assertFalse($obj->has_children());

        $children = array_fill(0, 4, (new Hierarchy()));
        $obj->set_children($children);
        $this->assertTrue($obj->has_children());
    }

    public function testNumChildren()
    {
        $obj = $this->obj;
        $this->assertEquals(0, $obj->num_children());

        $children = array_fill(0, 4, (new Hierarchy()));
        $obj->set_children($children);
        $this->assertEquals(4, $obj->num_children());

        $child5 = new Hierarchy();
        $obj->add_child($child5);
        $this->assertEquals(5, $obj->num_children());
    }

    public function testIsChildOf()
    {
        $obj = $this->obj;
        $master = new Hierarchy();

        $this->assertFalse($obj->is_child_of($master));
        $obj->set_master($master);
        $this->assertTrue($obj->is_child_of($master));
    }
}

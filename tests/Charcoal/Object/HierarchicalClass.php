<?php

namespace Charcoal\Tests\Object;

use \Charcoal\Object\HierarchicalInterface as HierarchicalInterface;
use \Charcoal\Object\HierarchicalTrait as HierarchicalTrait;

class HierarchicalClass implements HierarchicalInterface
{
    use HierarchicalTrait;

    public function set_children(array $children)
    {
        $this->_children = $children;
        return $this;
    }

    public function load_children()
    {
        return [];
    }
}

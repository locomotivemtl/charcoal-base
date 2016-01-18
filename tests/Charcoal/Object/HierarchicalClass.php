<?php

namespace Charcoal\Tests\Object;

use \Charcoal\Object\HierarchicalInterface as HierarchicalInterface;
use \Charcoal\Object\HierarchicalTrait as HierarchicalTrait;

class HierarchicalClass implements HierarchicalInterface
{
    use HierarchicalTrait;

    public function loadChildren()
    {
        return [];
    }
}

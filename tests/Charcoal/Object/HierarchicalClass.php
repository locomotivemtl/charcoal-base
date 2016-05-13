<?php

namespace Charcoal\Tests\Object;

use \Charcoal\Object\HierarchicalInterface;
use \Charcoal\Object\HierarchicalTrait;

class HierarchicalClass implements HierarchicalInterface
{
    use HierarchicalTrait;

    public function loadChildren()
    {
        return [];
    }

    public function modelFactory()
    {
        return null;
    }
}

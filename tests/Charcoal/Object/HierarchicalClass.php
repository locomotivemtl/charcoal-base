<?php

namespace Charcoal\Tests\Object;

use \Charcoal\Object\HierarchicalInterface;
use \Charcoal\Object\HierarchicalTrait;

class HierarchicalClass implements HierarchicalInterface
{
    use HierarchicalTrait;

    private $id;

    public function __construct($id = null)
    {
        if ($id === null) {
            $id = uniqid();
        }

        $this->setId($id);
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function id()
    {
        return $this->id;
    }

    public function loadChildren()
    {
        return [];
    }

    public function modelFactory()
    {
        return null;
    }
}

<?php

namespace Charcoal\Object;

// From `charcoal-core`
use \Charcoal\Model\AbstractModel as AbstractModel;
use \Charcoal\Core\IndexableInterface as IndexableInterface;
use \Charcoal\Core\IndexableTrait as IndexableTrait;

// From `charcoal-base`
use \Charcoal\Object\ObjectInterface as ObjectInterface;

abstract class AbstractObject extends AbstractModel implements ObjectInterface, IndexableInterface
{
    use IndexableTrait;

    public function set_data($data)
    {
        parent::set_data($data);
        $this->set_indexable_data($data);
        return $this;
    }
}

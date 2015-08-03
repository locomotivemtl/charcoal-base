<?php

namespace Charcoal\Object;

// Module `charcoal-core` dependencies
use \Charcoal\Model\AbstractModel as AbstractModel;
use \Charcoal\Core\IndexableInterface as IndexableInterface;
use \Charcoal\Core\IndexableTrait as IndexableTrait;

// Local namespace dependencies
use \Charcoal\Object\ObjectInterface as ObjectInterface;

/**
* Base (abstract) object class.
*
* Objects are specialized models that also implements the IndexableInterface.
*
* There is 2 default object type available in this module:
* - `Content`
* - `UserData`
*/
abstract class AbstractObject extends AbstractModel implements
    ObjectInterface,
    IndexableInterface
{
    use IndexableTrait;

    /**
    * @param array $data
    * @return AbstractObject Chainable
    */
    public function set_data(array $data)
    {
        $this->set_indexable_data($data);
        parent::set_data($data);
        return $this;
    }
}

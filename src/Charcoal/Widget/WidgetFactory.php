<?php

namespace Charcoal\Widget;

use \InvalidArgumentException as InvalidArgumentException;

// From `charcoal-core`
use \Charcoal\Core\AbstractFactory as AbstractFactory;

class WidgetFactory extends AbstractFactory
{
    public function create($type)
    {
        if (!is_string($type)) {
            throw new InvalidArgumentException('Type must be a string');
        }
        if (!$this->is_type_available($type)) {
            throw new InvalidArgumentException(sprintf('Type "%s" is not a valid type', $type));
        }
        $class_name = $this->_ident_to_classname($type);
        return new $class_name();
    }

    /**
    * Returns wether a type is available
    *
    * @param string $type The type to check
    * @return boolean True if the type is available, false if not
    */
    public function is_type_available($type)
    {
        $class_name = $this->_ident_to_classname($type);
        return class_exists($class_name);
    }
}

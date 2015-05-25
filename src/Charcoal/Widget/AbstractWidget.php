<?php

namespace Charcoal\Widget;

use \InvalidArgumentException as InvalidArgumentException;

use \Charcoal\Model\AbstractModel as AbstractModel;
use \Charcoal\Widget\WidgetInterface as WidgetInterface;

abstract class AbstractWidget extends AbstractModel implements WidgetInterface
{
    /**
    * @var boolean $_active
    */
    private $_active;

    /**
    * @param boolean $active
    * @throws InvalidArgumentException
    * @return AbstractWidget Chainable
    */
    public function set_active($active)
    {
        if (!is_bool($active)) {
            throw new InvalidArgumentException('Active must be a boolean');
        }
        $this->_active = $active;
        return $this;
    }

    /**
    * @return boolean
    */
    public function active()
    {
        return $this->_active;
    }
}


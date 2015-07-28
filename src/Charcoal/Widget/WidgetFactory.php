<?php

namespace Charcoal\Widget;

// Module `charcoal-core` dependencies
use \Charcoal\Core\AbstractFactory as AbstractFactory;

/**
*
*/
class WidgetFactory extends AbstractFactory
{
    /**
    * @param array $data
    */
    public function __construct(array $data = null)
    {
        $this->set_factory_mode(AbstractFactory::MODE_IDENT);
        $this->set_base_class('\Charcoal\Widget\WidgetInterface');

        if ($data !== null) {
            $this->set_data($data);
        }
    }

    /**
    * AbstractFactory > factory_class()
    *
    * @param string
    * @return string
    */
    public function factory_class($class)
    {
        return $class.'Widget';
    }
}

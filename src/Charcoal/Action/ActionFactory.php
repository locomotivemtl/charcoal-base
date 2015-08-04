<?php

namespace Charcoal\Action;

// Module `charcoal-core` dependencies
use \Charcoal\Core\AbstractFactory as AbstractFactory;

/**
* The ActionFactory creates Action objects.
*
* @see \Charcoal\Core\FactoryInterface
*/
class ActionFactory extends AbstractFactory
{
    /**
    * @param array $data
    */
    public function __construct(array $data = null)
    {
        $this->set_factory_mode(AbstractFactory::MODE_IDENT);
        $this->set_base_class('\Charcoal\Action\ActionInterface');

        if ($data !== null) {
            $this->set_data($data);
        }
    }

    /**
    * AbstractFactory > factory_class()
    *
    * Actions class names are always suffixed with "Action".
    *
    * @param string $class
    * @return string
    */
    public function factory_class($class)
    {
        return $class.'Action';
    }
}

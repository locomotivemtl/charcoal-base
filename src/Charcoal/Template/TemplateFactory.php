<?php

namespace Charcoal\Template;

// Module `charcoal-core` dependencies
use \Charcoal\Core\AbstractFactory as AbstractFactory;

/**
*
*/
class TemplateFactory extends AbstractFactory
{
    /**
    * @param array $data
    */
    public function __construct(array $data = null)
    {
        $this->set_factory_mode(AbstractFactory::MODE_IDENT);
        $this->set_base_class('\Charcoal\Template\TemplateInterface');
        //$this->set_default_class('\Charcoal\Template\Template');

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
        return $class.'Template';
    }
}

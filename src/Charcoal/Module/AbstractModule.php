<?php

namespace Charcoal\Module;

use \Charcoal\Module\ModuleInterface as ModuleInterface;

abstract class AbstractModule implements ModuleInterface
{
    /**
    * @param aray $data
    */
    public function __construct($data=null)
    {
        $this->init($data);
    }

    abstract public function init($opts=null);
}

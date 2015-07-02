<?php

namespace Charcoal\Module;

use \Charcoal\Module\ModuleInterface as ModuleInterface;

abstract class AbstractModule implements ModuleInterface
{
    /**
    * @param aray $data
    */
    public function __construct(array $data = null)
    {
        $this->init($data);
    }

    /**
    * @param array $opts
    * @return void
    */
    abstract public function init(array $opts = null);
}

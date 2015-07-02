<?php

namespace Charcoal\Module;

interface ModuleInterface
{

    /**
    * @param array $data
    * @return ModuleInterface Chainable
    */
    public function init(array $data = null);

    /**
    * @return ModuleInterface Chainable
    */
    public function setup_routes();

    /**
    * @return ModuleInterface Chainable
    */
    public function setup_cli_routes();
}

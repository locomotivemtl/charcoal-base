<?php

namespace Charcoal\Module;

interface ModuleInterface
{

    /**
    * @param array $data
    * @return ModuleInterface Chainable
    */
    public function init($data);

    /**
    * @return ModuleInterface Chainable
    */
    public function setup_routes();

    /**
    * @return ModuleInterface Chainable
    */
    public function setup_cli_routes();
}

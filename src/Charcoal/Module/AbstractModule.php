<?php

namespace Charcoal\Module;

use \Charcoal\Module\ModuleInterface as ModuleInterface;

abstract class AbstractModule implements ModuleInterface
{
    abstract static public function init($opts=null);
}

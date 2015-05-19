<?php

namespace Charcoal\Action;

use \Exception as Exception;

// From `charcoal-core`
use \Charcoal\Core\AbstractFactory as AbstractFactory;

/**
*
*/
class ActionFactory extends AbstractFactory
{
    public function get($type)
    {
        $class_name = str_replace('/', '\\', ucfirst($type));
        if (class_exists($class_name)) {
            $obj = new $class_name();
            if (!($obj instanceof ActionInterface)) {
                throw new Exception('Invalid action: '.$type);
            }
            return $obj;
        } else {
            throw new Exception('Invalid action: '.$type);
        }
    }
}

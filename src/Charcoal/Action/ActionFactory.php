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
        $class_name = $this->_ident_to_classname($type);
        if (class_exists($class_name)) {
            $obj = new $class_name();
            if (!($obj instanceof ActionInterface)) {
                throw new Exception('Invalid action: '.$type.' (not an action)');
            }
            return $obj;
        } else {
            throw new Exception('Invalid action: '.$type);
        }
    }

    /**
    * @param string @ident
    * @return string
    */
    protected function _ident_to_classname($ident)
    {
        $class = str_replace('/', '\\', $ident);
        $expl = explode('\\', $class);
        array_walk(
            $expl, function(&$i) {
                $i = ucfirst($i);
            }
        );
        $class = '\\'.implode('\\', $expl);
        return $class;
    }
}

<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \InvalidArgumentException as InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO;

// Local namespace dependencies
use \Charcoal\Property\AbstractProperty;

/**
 * Audio Property.
 */
class MapStructureProperty extends AbstractProperty
{

    /**
     * @return string
     */
    public function type()
    {
        return 'map-structure';
    }


    /**
     * @return string
     */
    public function sql_extra()
    {
        return '';
    }

    /**
     * @return string
     */
    public function sql_type()
    {
        return 'TEXT';
    }

    /**
     * @return integer
     */
    public function sql_pdo_type()
    {
        return PDO::PARAM_STR;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        return $this->val();
    }
}

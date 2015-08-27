<?php

namespace Charcoal\Property;

use \InvalidArgumentException as InvalidArgumentException;

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
    * @param array $data
    * @return AudioProperty Chainable
    */
    public function set_data(array $data)
    {

        parent::set_data($data);

        return $this;
    }

        /**
    * @return string
    */
    public function sql_extra() {
        return '';
    }

    /**
    * @return string
    */
    public function sql_type() {
        return 'TEXT';
    }

    /**
    * @return integer
    */
    public function sql_pdo_type() {
        return PDO::PARAM_STR;
    }

    /**
    * @return mixed
    */
    public function save() {
        return $this->val();
    }

}

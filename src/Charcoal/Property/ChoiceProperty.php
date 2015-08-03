<?php

namespace Charcoal\Property;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty as AbstractProperty;

/**
* Choice Property
*/
class ChoiceProperty extends AbstractProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'choice';
    }

        /**
    * @return string
    */
    public function sql_extra()
    {
        return '';
    }

    /**
    * Get the SQL type (Storage format)
    *
    * Stored as `TEXT` for now
    *
    * @return string The SQL type
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

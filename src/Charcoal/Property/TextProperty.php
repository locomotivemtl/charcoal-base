<?php

namespace Charcoal\Property;

// Local namespace dependencies
use \Charcoal\Property\StringProperty;

/**
* Text Property.
*/
class TextProperty extends StringProperty
{

    /**
    * @return string
    */
    public function type()
    {
        return 'text';
    }

    /**
    * @return integer
    */
    public function default_max_length()
    {
        return 0;
    }

    /**
    * Get the SQL type (Storage format)
    *
    * @return string The SQL type
    */
    public function sql_type()
    {
        return 'TEXT';
    }
}

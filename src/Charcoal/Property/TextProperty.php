<?php

namespace Charcoal\Property;

// Local namespace dependencies
use \Charcoal\Property\StringProperty as StringProperty;

/**
* Text Property.
*/
class TextProperty extends StringProperty
{

    /**
    * @return integer
    */
    public function default_max_length()
    {
        return 0;
    }

    /**
    * @return string
    */
    public function type()
    {
        return 'text';
    }

        /**
    * Get the SQL type (Storage format)
    *
    * Stored as `VARCHAR` for max_length under 255 and `TEXT` for other, longer strings
    *
    * @return string The SQL type
    */
    public function sql_type()
    {
        return 'TEXT';
    }
}

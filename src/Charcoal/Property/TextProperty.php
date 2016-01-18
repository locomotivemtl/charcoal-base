<?php

namespace Charcoal\Property;

// Local namespace dependencies
use \Charcoal\Property\StringProperty;

/**
 * Text Property. Longer strings.
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
    public function defaultMaxLength()
    {
        return 0;
    }

    /**
     * Get the SQL type (Storage format)
     *
     * @return string The SQL type
     */
    public function sqlType()
    {
        return 'TEXT';
    }
}

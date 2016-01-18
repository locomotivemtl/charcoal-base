<?php

namespace Charcoal\Property;

// Local namespace dependencies
use \Charcoal\Property\StringProperty;

/**
 * HTML Property.
 *
 * The html property is a specialized string property.
 */
class HtmlProperty extends StringProperty
{

    /**
     * @return string
     */
    public function type()
    {
        return 'html';
    }

    /**
     * Unlike strings' upper limit of 255, HTML has no defualt max length (0).
     *
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

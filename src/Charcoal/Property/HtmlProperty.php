<?php

namespace Charcoal\Property;

// Local namespace dependencies
use \Charcoal\Property\StringProperty as StringProperty;

/**
* HTML Property
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
}

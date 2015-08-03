<?php

namespace Charcoal\Property;

// Local namespace dependencies
use \Charcoal\Property\StringProperty as StringProperty;

/**
* Password Property
*
* The password property is a specialized string property.
*/
class PasswordProperty extends StringProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'password';
    }
}

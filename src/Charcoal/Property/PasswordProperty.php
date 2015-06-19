<?php

namespace Charcoal\Property;

/**
*
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

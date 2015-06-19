<?php

namespace Charcoal\Property;

use \Charcoal\Property\StringProperty as StringProperty;

class TextProperty extends StringProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'text';
    }
}

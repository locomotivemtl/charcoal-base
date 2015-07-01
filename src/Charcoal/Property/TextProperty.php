<?php

namespace Charcoal\Property;

// From `charcoal-base`
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

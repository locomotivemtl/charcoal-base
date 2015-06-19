<?php

namespace Charcoal\Property;

// In charcoal-core
use \Charcoal\Property\AbstractProperty as AbstractProperty;

class ImageProperty extends AbstractProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'image';
    }
}

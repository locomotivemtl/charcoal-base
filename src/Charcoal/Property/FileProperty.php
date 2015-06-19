<?php

namespace Charcoal\Property;

// In charcoal-core
use \Charcoal\Property\AbstractProperty as AbstractProperty;

class FileProperty extends AbstractProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'file';
    }
}

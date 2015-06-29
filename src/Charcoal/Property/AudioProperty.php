<?php

namespace Charcoal\Property;

// From `charcoal-admin`
use \Charcoal\Property\FileProperty as FileProperty;

class AudioProperty extends FileProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'audio';
    }
}

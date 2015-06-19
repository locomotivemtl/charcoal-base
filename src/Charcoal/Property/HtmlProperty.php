<?php

namespace Charcoal\Property;

// In charcoal-core
use \Charcoal\Property\StringProperty as StringProperty;

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

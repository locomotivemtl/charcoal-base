<?php

namespace Charcoal\Object;

/**
*
*/
trait RoutableTrait
{
    /**
    * @param array $data
    * @return RoutableInterface Chainable
    */
    public function set_routable_data(array $data)
    {
        return $this;
    }
}

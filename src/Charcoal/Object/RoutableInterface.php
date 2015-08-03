<?php

namespace Charcoal\Object;

interface RoutableInterface
{
    /**
    * @param array $data
    * @return RoutableInterface Chainable
    */
    public function set_routable_data(array $data);
}

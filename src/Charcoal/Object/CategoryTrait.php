<?php

namespace Charcoal\Model;

/**
*
*/
trait CategoryTrait
{

    /**
    * @param array
    */
    public function set_category_data(array $data)
    {
        return $this;
    }

    /**
    * @return string
    */
    abstract public function item_type();

    /**
    * @return integer
    */
    abstract public function num_items();

    /**
    * @return boolean
    */
    public function has_items()
    {
        $num_items = $this->num_items();
        return ($num_items > 0);
    }

    /**
    * @return array
    */
    abstract public function items();
}

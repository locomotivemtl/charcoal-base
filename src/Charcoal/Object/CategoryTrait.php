<?php

namespace Charcoal\Object;

/**
*
*/
trait CategoryTrait
{
    /**
    * @var array $category_items
    */
    private $category_items;

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
    public function num_items()
    {
        $items = $this->items();
        return count($items);
    }

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
    public function items()
    {
        if ($this->category_items == null) {
            $this->category_items = $this->load_items();
        }
        return $this->category_items;
    }

    abstract public function load_items();
}

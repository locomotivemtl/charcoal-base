<?php

namespace Charcoal\Object;

interface CategoryInterface
{
    /**
    * @return string
    */
    public function item_type();

    /**
    * Get the number of items in this category.
    * @param array
    */
    public function num_items();

    /**
    * @return boolean
    */
    public function has_items();

    /**
    * @return array
    * @todo Return Collection?
    */
    public function items();
}

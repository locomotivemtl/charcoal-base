<?php

namespace Charcoal\Object;

interface CategoryInterface
{
    /**
     * @param string $type The category item type.
     * @return CategoryInterface Chainable
     */
    public function set_category_item_type($type);

    /**
     * @return string
     */
    public function category_item_type();

    /**
     * Get the number of items in this category.
     * @param array
     */
    public function num_category_items();

    /**
     * @return boolean
     */
    public function has_category_items();

    /**
     * @return array
     * @todo Return Collection?
     */
    public function category_items();
}

<?php

namespace Charcoal\Object;

/**
*
*/
interface CategorizableInterface
{
    /**
    * @return string
    */
    public function category_type();

    /**
    * @param array $data
    * @return CategorizableInterface Chainable
    */
    public function set_categorizable_data(array $data);

    /**
    * @param mixed $category
    * @return CategorizableInterface Chainable
    */
    public function set_category($category);

    /**
    * Get the category object
    * @return CategoryInterface
    */
    public function category();

    /**
    * Get the categories list
    * @return array
    */
    //public function categories();
}

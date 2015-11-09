<?php

namespace Charcoal\Object;

/**
* The `Categorizable` Trait / Interface describes objects that can be put into categories.
*/
interface CategorizableInterface
{
    /**
    * @param string $type
    * @throws InvalidArgumentException
    * @return CategorizableInterface Chainable
    */
    public function set_category_type($type);

    /**
    * @return string
    */
    public function category_type();

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
}

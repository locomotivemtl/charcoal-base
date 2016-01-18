<?php

namespace Charcoal\Object;

/**
 * The `Categorizable` Trait / Interface describes objects that can be put into categories.
 */
interface CategorizableInterface
{
    /**
     * @param string $type The category type.
     * @return CategorizableInterface Chainable
     */
    public function setCategoryType($type);

    /**
     * @return string
     */
    public function categoryType();

    /**
     * @param mixed $category The object's category.
     * @return CategorizableInterface Chainable
     */
    public function setCategory($category);

    /**
     * Get the category object
     * @return CategoryInterface
     */
    public function category();
}

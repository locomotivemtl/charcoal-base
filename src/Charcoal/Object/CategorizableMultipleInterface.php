<?php

namespace Charcoal\Object;

/**
 * The `Categorizable` Trait / Interface describes objects that can be put into categories.
 */
interface CategorizableMultipleInterface
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
     * @param mixed $categories The object's categories.
     * @return CategorizableInterface Chainable
     */
    public function setCategories($categories);

    /**
     * Get the category object
     * @return CategoryInterface
     */
    public function categories();
}

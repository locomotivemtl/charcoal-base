<?php

namespace Charcoal\Object;

use \InvalidArgumentException;

/**
 * Categorizable defines objects that can be in a `Category`.
 */
trait CategorizableMultipleTrait
{
    /**
     * @var string $categoryType
     */
    private $categoryType;

    /**
     * @var mixed $category
     */
    private $categories;

    /**
     * @param string $type The category type.
     * @throws InvalidArgumentException If the type argument is not a string.
     * @return CategorizableInterface Chainable
     */
    public function setCategoryType($type)
    {
        if (!is_string($type)) {
            throw new InvalidArgumentException(
                'Category type must be a string.'
            );
        }
        $this->categoryType = $type;
        return $this;
    }
    /**
     * @return string
     */
    public function categoryType()
    {
        return $this->categoryType;
    }

    /**
     * @param mixed[] $categories The object's categories.
     * @return CategorizableInterface Chainable
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return CategoryInterface
     */
    public function categories()
    {
        return $this->categories;
    }
}

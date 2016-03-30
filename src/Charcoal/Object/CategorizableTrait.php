<?php

namespace Charcoal\Object;

use \InvalidArgumentException;

/**
 * Categorizable defines objects that can be in a `Category`.
 */
trait CategorizableTrait
{
    /**
     * @var string $categoryType
     */
    private $categoryType;

    /**
     * @var mixed $category
     */
    private $category;

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
     * @param mixed $category The object's category.
     * @return CategorizableInterface Chainable
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return CategoryInterface
     */
    public function category()
    {
        return $this->category;
    }
}

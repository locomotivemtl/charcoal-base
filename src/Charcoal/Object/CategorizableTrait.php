<?php

namespace Charcoal\Object;

use \InvalidArgumentException;

/**
* Categorizable defines objects that can be in a `Category`.
*/
trait CategorizableTrait
{
    /**
     * @var string $category_type
     */
    private $category_type;

    /**
     * @var mixed $category
     */
    private $category;

    /**
     * @param string $type The category type.
     * @throws InvalidArgumentException If the type argument is not a string.
     * @return CategorizableInterface Chainable
     */
    public function set_category_type($type)
    {
        if (!is_string($type)) {
            throw new InvalidArgumentException(
                'Category type must be a string.'
            );
        }
        $this->category_type = $type;
        return $this;
    }
    /**
     * @return string
     */
    public function category_type()
    {
        return $this->category_type;
    }

    /**
     * @param mixed $category The object's category.
     * @return CategorizableInterface Chainable
     */
    public function set_category($category)
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

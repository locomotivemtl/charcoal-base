<?php

namespace Charcoal\Object;

use Exception;
use InvalidArgumentException;

/**
*
*/
trait CategoryTrait
{
    /**
     * @var string $itemType
     */
    private $categoryItemType;

    /**
     * @var Collection $categoryItems
     */
    private $categoryItems;

    /**
     * @param string $type The category item type.
     * @throws InvalidArgumentException If the type argument is not a string.
     * @return CategoryInterface Chainable
     */
    public function setCategoryItemType($type)
    {
        if (!is_string($type)) {
            throw new InvalidArgumentException(
                'Item type must be a string.'
            );
        }
        $this->categoryItemType = $type;
        return $this;
    }

    /**
     * @throws Exception If no item type was previously set.
     * @return string
     */
    public function categoryItemType()
    {
        if ($this->categoryItemType === null) {
            throw new Exception(
                'Item type is unset. Set item type before calling getter.'
            );
        }
        return $this->categoryItemType;
    }

    /**
     * @return integer
     */
    public function numCategoryItems()
    {
        $items = $this->categoryItems();
        return count($items);
    }

    /**
     * @return boolean
     */
    public function hasCategoryItems()
    {
        $numItems = $this->numCategoryItems();
        return ($numItems > 0);
    }

    /**
     * @return Collection A list of `CategorizableInterface` objects
     */
    public function categoryItems()
    {
        if ($this->categoryItems == null) {
            $this->categoryItems = $this->loadCategoryItems();
        }
        return $this->categoryItems;
    }

    /**
     * @return Collection
     */
    abstract public function loadCategoryItems();
}

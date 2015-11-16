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
    * @var string $item_type
    */
    private $category_item_type;

    /**
    * @var Collection $category_items
    */
    private $category_items;

    /**
    * @param string $type
    * @throws InvalidArgumentException
    * @return CategoryInterface Chainable
    */
    public function set_category_item_type($type)
    {
        if (!is_string($type)) {
            throw new InvalidArgumentException(
            );
        }
        $this->category_item_type = $type;
        return $this;
    }

    /**
    * @throws Exception If no item type was previously set.
    * @return string
    */
    public function category_item_type()
    {
        if ($this->category_item_type === null) {
            throw new Exception(
                'Item type is unset. Set item type before calling getter.'
            );
        }
        return $this->category_item_type;
    }

    /**
    * @return integer
    */
    public function num_category_items()
    {
        $items = $this->category_items();
        return count($items);
    }

    /**
    * @return boolean
    */
    public function has_category_items()
    {
        $num_items = $this->num_category_items();
        return ($num_items > 0);
    }

    /**
    * @return Collection A list of `CategorizableInterface` objects
    */
    public function category_items()
    {
        if ($this->category_items == null) {
            $this->category_items = $this->load_category_items();
        }
        return $this->category_items;
    }

    /**
    * @return Collection
    */
    abstract public function load_category_items();
}

<?php

namespace Charcoal\Object;

/**
*
*/
trait CategorizableTrait
{
    /**
    * @var mixed $_category
    */
    protected $_category;

    /**
    * @param array $data
    * @return CategorizableTrait Chainable
    */
    public function set_categorizable_data(array $data)
    {
        if (isset($data['category']) && $data['category'] !== null) {
            $this->set_category($data['category']);
        }
        return $this;
    }

    /**
    * @param mixed $category
    * @return CategorizableTrait
    */
    public function set_category($category)
    {
        $this->_category = $category;
        return $this;
    }

    /**
    * @return CategoryInterface
    */
    public function category()
    {
        return $this->_category;
    }
}

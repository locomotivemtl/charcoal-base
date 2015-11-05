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
    * @return string
    */
    abstract public function category_type();

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

    /**
    * @return string
    */
    abstract public function category_type();
}

<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \InvalidArgumentException as InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO as PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty as AbstractProperty;

/**
* String Property
*/
class NumberProperty extends AbstractProperty
{
    const DEFAULT_ALLOW_EMPTY = true;

    /**
    * @var boolean $_allow_empty
    */
    private $allow_empty;

    /**
    * @return string
    */
    public function type()
    {
        return 'number';
    }

    /**
    * @param bool $allow_empty
    * @throws InvalidArgumentException If parameter is invalid
    * @return StringProperty Chainable
    */
    public function set_allow_empty($allow_empty)
    {
        $this->allow_empty = !!$allow_empty;
        return $this;
    }

    /**
    * @return bool
    */
    public function allow_empty()
    {
        if ($this->allow_empty === null) {
            $this->allow_empty = self::DEFAULT_ALLOW_EMPTY;
        }
        return $this->allow_empty;
    }

    /**
    * @return string
    */
    public function sql_extra()
    {
        return '';
    }

    /**
    * Get the SQL type (Storage format)
    *
    * Stored as `VARCHAR` for max_length under 255 and `TEXT` for other, longer strings
    *
    * @return string The SQL type
    */
    public function sql_type()
    {
        // Multiple number are stocked as TEXT because we do not know the maximum length
        if ($this->multiple()) {
            return 'TEXT';
        }

        return 'DOUBLE';
    }

    /**
    * @return integer
    */
    public function sql_pdo_type()
    {
        return PDO::PARAM_STR;
    }

    /**
    * @return mixed
    */
    public function save()
    {
        return $this->val();
    }
}

<?php

namespace Charcoal\Property;

use \PDO as PDO;

// In charcoal-core
use \Charcoal\Property\AbstractProperty as AbstractProperty;

class AudioProperty extends AbstractProperty
{
    /**
    * @return string
    */
    public function type()
    {
        return 'audio';
    }

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
        // Multiple strings are always stored as TEXT because they can hold multiple values
        if ($this->multiple()) {
            return 'TEXT';
        }else{
            return 'VARCHAR(255)';
        }
    }

    public function sql_pdo_type()
    {
        return PDO::PARAM_STR;
    }

    public function save()
    {
        return $this->val();
    }
}

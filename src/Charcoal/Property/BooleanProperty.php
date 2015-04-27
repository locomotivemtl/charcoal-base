<?php

namespace Charcoal\Property;

use \Charcoal\Model\Property as Property;
use \Charcoal\Model\Validator\Propertyalidator as Validator;

/**
*
*/
class BooleanProperty extends Property
{
    /**
    * @var mixed $_true_label
    */
    private $_true_label;
    /**
    * @var mixed $_false_label
    */
    private $_false_label;

    /**
    * @param array $data
    * @throws \InvalidArgumentException if the parameter is not an array
    * @return String (Chainable)
    */
    public function set_data($data)
    {

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data must be an array');
        }

        parent::set_data($data);

        if (isset($data['true_label']) && $data['true_label'] !== null) {
            $this->set_true_label($data['true_label']);
        }
        if (isset($data['false_label']) && $data['false_label'] !== null) {
            $this->set_false_label($data['false_label']);
        }
        return $this;
    }

    public function set_true_label($label)
    {
        $this->_true_label = $label;
        return $this;
    }

    public function true_label()
    {
        return $this->_true_label;
    }

    public function set_false_label($label)
    {
        $this->_false_label = $label;
        return $this;
    }

    public function false_label()
    {
        return $this->_false_label;
    }

    /**
    * Get the SQL type (Storage format)
    *
    * Stored as `TINYINT(1) UNSIGNED`
    *
    * @return string The SQL type
    */
    public function sql_type()
    {
        return 'TINYINT(1) UNSIGNED';
    }
}

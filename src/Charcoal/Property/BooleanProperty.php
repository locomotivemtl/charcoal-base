<?php

namespace Charcoal\Property;

use \PDO as PDO;

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
    * @return string
    */
    public function type()
    {
        return 'boolean';
    }

    /**
    * @param array $data
    * @return BooleanProperty Chainable
    */
    public function set_data(array $data)
    {
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

    public function sql_pdo_type()
    {
        return PDO::PARAM_BOOL;
    }

    public function choices()
    {
        return [
            [
                'label'=>'True',
                'selected'=>!!($this->val()),
                'value'=>1
            ],
            [
                'label'=>'False',
                'selected'=>!($this->val()),
                'value'=>0
            ]
        ];
    }
}

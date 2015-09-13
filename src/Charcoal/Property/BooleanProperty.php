<?php

namespace Charcoal\Property;

// Dependencies from `PHP` extensions
use \PDO as PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty;
use \Charcoal\Translation\TranslationString;

/**
* Boolean Property
*/
class BooleanProperty extends AbstractProperty
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
    * @param mixed $label
    * @return BooleanProperty
    */
    public function set_true_label($label)
    {
        $this->_true_label = new TranslationString($label);
        return $this;
    }

    /**
    * @return mixed
    */
    public function true_label()
    {
        if ($this->_true_label === null) {
            $this->set_true_label('True');
        }
        return $this->_true_label;
    }

    /**
    * @param mixed $label
    * @return BooleanProperty
    */
    public function set_false_label($label)
    {
        $this->_false_label = $label;
        return $this;
    }

    /**
    * @return mixed
    */
    public function false_label()
    {
        if ($this->_false_label === null) {
            $this->set_false_label('False');
        }
        return $this->_false_label;
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
    * Stored as `TINYINT(1) UNSIGNED`
    *
    * @return string The SQL type
    */
    public function sql_type()
    {
        return 'TINYINT(1) UNSIGNED';
    }

    /**
    * @return integer
    */
    public function sql_pdo_type()
    {
        return PDO::PARAM_BOOL;
    }

    /**
    * @return array
    */
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

    /**
    * @return mixed
    */
    public function save()
    {
        return $this->val();
    }
}

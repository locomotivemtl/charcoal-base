<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \InvalidArgumentException;

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
     * @var TranslationString $trueLabel
     */
    private $trueLabel;
    /**
     * @var TranslationString $falseLabel
     */
    private $falseLabel;

    /**
     * @return string
     */
    public function type()
    {
        return 'boolean';
    }

    /**
     * @param mixed $val Optional. The value to display. If non provided, use `val()`.
     * @return string
     */
    public function displayVal($val = null)
    {
        if ($val === null) {
            $val = $this->val();
        }

        if ($val === true) {
            return $this->trueLabel();
        } else {
            return $this->falseLabel();
        }
    }

    /**
     * AbstractProperty > set_multiple()
     *
     * Ensure multiple can not be true for Datetime property.
     *
     * @param boolean $multiple The multiple flag.
     * @throws InvalidArgumentException If multiple is true. (must be false for boolean properties).
     * @return BooleanProperty Chainable
     */
    public function setMultiple($multiple)
    {
        $multiple = !!$multiple;
        if ($multiple === true) {
            throw new InvalidArgumentException(
                'Multiple can not be true for boolean property.'
            );
        }
        return $this;
    }

    /**
     * AbstractProperty > multiple()
     *
     * Multiple is always false for Boolean property.
     *
     * @return boolean
     */
    public function multiple()
    {
        return false;
    }

    /**
     * @param mixed $label The true label.
     * @return BooleanProperty Chainable
     */
    public function setTrueLabel($label)
    {
        $this->trueLabel = new TranslationString($label);
        return $this;
    }

    /**
     * @return mixed
     */
    public function trueLabel()
    {
        if ($this->trueLabel === null) {
            // Default value
            $this->setTrueLabel('True');
        }
        return $this->trueLabel;
    }

    /**
     * @param mixed $label The false label.
     * @return BooleanProperty Chainable
     */
    public function setFalseLabel($label)
    {
        $this->falseLabel = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function falseLabel()
    {
        if ($this->falseLabel === null) {
            // Default value
            $this->setFalseLabel('False');
        }
        return $this->falseLabel;
    }

    /**
     * @return string
     */
    public function sqlExtra()
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
    public function sqlType()
    {
        return 'TINYINT(1) UNSIGNED';
    }

    /**
     * @return integer
     */
    public function sqlPdoType()
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
                'label'     => 'True',
                'selected'  => !!($this->val()),
                'value'     => 1
            ],
            [
                'label'     => 'False',
                'selected'  => !($this->val()),
                'value'     => 0
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

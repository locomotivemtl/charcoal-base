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
     * @var TranslationString $true_label
     */
    private $true_label;
    /**
     * @var TranslationString $false_label
     */
    private $false_label;

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
    public function display_val($val = null)
    {
        if ($val === null) {
            $val = $this->val();
        }

        if ($val === true) {
            return $this->true_label();
        } else {
            return $this->false_label();
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
    public function set_multiple($multiple)
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
    public function set_true_label($label)
    {
        $this->true_label = new TranslationString($label);
        return $this;
    }

    /**
     * @return mixed
     */
    public function true_label()
    {
        if ($this->true_label === null) {
            // Default value
            $this->set_true_label('True');
        }
        return $this->true_label;
    }

    /**
     * @param mixed $label The false label.
     * @return BooleanProperty Chainable
     */
    public function set_false_label($label)
    {
        $this->false_label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function false_label()
    {
        if ($this->false_label === null) {
            // Default value
            $this->set_false_label('False');
        }
        return $this->false_label;
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

<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \Exception;
use \InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Core\StringFormat;
use \Charcoal\Property\AbstractProperty;
use \Charcoal\Translation\TranslationConfig;

// Local namespace dependencies
use \Charcoal\Property\SelectablePropertyInterface;
use \Charcoal\Property\SelectablePropertyTrait;

/**
 * String Property
 */
class StringProperty extends AbstractProperty implements SelectablePropertyInterface
{
    use SelectablePropertyTrait;

    const DEFAULT_MIN_LENGTH = 0;
    const DEFAULT_MAX_LENGTH = 255;
    const DEFAULT_REGEXP = '';
    const DEFAULT_ALLOW_EMPTY = true;

    /**
     * @var int $_min_length
     */
    private $min_length;
    /**
     * @var int $_max_length
     */
    private $max_length;
    /**
     * Defines a validation regular expression for this string.
     * @var string $regexp
     */
    private $regexp;

    /**
     * @var boolean $allow_empty
     */
    private $allow_empty;

    /**
     * @var StringFormat $formatter
     */
    private $formatter;

    /**
     * @return string
     */
    public function type()
    {
        return 'string';
    }

    /**
     * @param mixed $val Optional. The value to display. If null/unset, use `val()`.
     * @return string
     * @see AbstractProperty::display_val()
     */
    public function display_val($val = null)
    {
        if ($val === null) {
            $val = $this->val();
        }

        if ($val === null) {
            return '';
        }

        $property_value = $val;

        if ($this->l10n() === true) {
            $translator = TranslationConfig::instance();

            $property_value = $property_value[$translator->current_language()];
        }

        if ($this->multiple() === true) {
            if (is_array($property_value)) {
                $props = [];
                foreach ($property_value as $pv) {
                    ;
                    $props[] = $this->val_label($pv);
                }
                $property_value = implode($this->multiple_separator(), $props);
            }
        } else {
            $property_value = (string)$property_value;
            $property_value = $this->val_label($property_value);
        }
        return $property_value;
    }

    /**
     * Attempt to get the label from choices. Otherwise, return the raw value.
     *
     * @param string $val The value to retrieve the label of.
     * @return string
     */
    protected function val_label($val)
    {
        if ($this->has_choice($val)) {
            $choice = $this->choice($val);
            return $choice['label'];
        } else {
            return $val;
        }
    }

    /**
     * @param integer $max_length The max length allowed.
     * @throws InvalidArgumentException If the parameter is not an integer or < 0.
     * @return StringProperty Chainable
     */
    public function set_max_length($max_length)
    {
        if (!is_integer($max_length)) {
            throw new InvalidArgumentException(
                'Max length must be an integer.'
            );
        }
        if ($max_length < 0) {
            throw new InvalidArgumentException(
                'Max length must be a positive integer (>=0).'
            );
        }
        $this->max_length = $max_length;
        return $this;
    }

    /**
     * @return integer
     */
    public function max_length()
    {
        if ($this->max_length === null) {
            $this->max_length = $this->default_max_length();
        }
        return $this->max_length;
    }

    /**
     * @return integer
     */
    public function default_max_length()
    {
        return 255;
    }

    /**
     * @param integer $min_length The minimum length allowed.
     * @throws InvalidArgumentException If the parameter is not an integer or < 0.
     * @return StringProperty Chainable
     */
    public function set_min_length($min_length)
    {
        if (!is_integer($min_length)) {
            throw new InvalidArgumentException(
                'Min length must be an integer.'
            );
        }
        if ($min_length < 0) {
            throw new InvalidArgumentException(
                'Min length must be a positive integer (>=0).'
            );
        }
        $this->min_length = $min_length;
        return $this;
    }

    /**
     * @return integer
     */
    public function min_length()
    {
        if ($this->min_length === null) {
            $this->min_length = self::DEFAULT_MIN_LENGTH;
        }
        return $this->min_length;
    }

    /**
     * @param string $regexp The allowed regular expression.
     * @throws InvalidArgumentException If the parameter is not a string.
     * @return StringProperty Chainable
     */
    public function set_regexp($regexp)
    {
        if (!is_string($regexp)) {
            throw new InvalidArgumentException(
                'Regular expression must be a string.'
            );
        }
        $this->regexp = $regexp;
        return $this;
    }

    /**
     * @return string
     */
    public function regexp()
    {
        if ($this->regexp === null) {
            $this->regexp = self::DEFAULT_REGEXP;
        }
        return $this->regexp;
    }

    /**
     * @param boolean $allow_empty The allow empty flag.
     * @return StringProperty Chainable
     */
    public function set_allow_empty($allow_empty)
    {
        $this->allow_empty = !!$allow_empty;
        return $this;
    }

    /**
     * @return boolean
     */
    public function allow_empty()
    {
        if ($this->allow_empty === null) {
            $this->allow_empty = self::DEFAULT_ALLOW_EMPTY;
        }
        return $this->allow_empty;
    }

    /**
     * @return StringFormat
     */
    public function formatter()
    {
        if ($this->formatter === null) {
            $this->formatter = new StringFormat();
        }
        $this->formatter->set_string($this->val());
        return $this->formatter;
    }

    /**
     * @todo Support l10n values
     * @todo Support multiple values
     * @throws Exception If val is not a string.
     * @return integer
     */
    public function length()
    {
        $val = $this->val();
        if (!is_string($val)) {
            throw new Exception(
                'Can not get string length: val is not a string'
            );
        }
        return mb_strlen($val);
    }

    /**
     * @return array
     */
    public function validation_methods()
    {
        $parent_methods = parent::validation_methods();
        return array_merge($parent_methods, ['max_length', 'min_length', 'regexp', 'allow_empty']);
    }

    /**
     * @return boolean
     * @todo Support `multiple` / `l10n`
     */
    public function validate_max_length()
    {
        $val = $this->val();

        if ($val === null) {
            return true;
        }

        $max_length = $this->max_length();
        if ($max_length == 0) {
            return true;
        }

        if (is_string($val)) {
            $valid = (mb_strlen($val) <= $max_length);
            if (!$valid) {
                $this->validator()->error('Max length error', 'max_length');
            }
        } else {
            $valid = true;
            foreach ($val as $v) {
                $valid = (mb_strlen($v) <= $max_length);
                if (!$valid) {
                    $this->validator()->error('Max length error', 'max_length');
                    return $valid;
                }
            }
        }

        return $valid;

    }

    /**
     * @return boolean
     * @todo Support `multiple` / `l10n`
     */
    public function validate_min_length()
    {
        $val = $this->val();
        $min_length = $this->min_length();
        if ($min_length == 0) {
            return true;
        }

        if ($val === '' && $this->allow_empty()) {
            // Don't check empty string if they are allowed
            return true;
        }

        $valid = (mb_strlen($val) >= $min_length);
        if (!$valid) {
            $this->validator()->error('Min length error', 'min_length');
        }

        return $valid;
    }

    /**
     * @return boolean
     */
    public function validate_regexp()
    {
        $val = $this->val();
        $regexp = $this->regexp();
        if ($regexp == '') {
            return true;
        }

        $valid = !!preg_match($regexp, $val);
        if (!$valid) {
            $this->validator()->error('Regexp error', 'regexp');
        }

        return $valid;
    }

    /**
     * @return boolean
     */
    public function validate_allow_empty()
    {
        if (($this->val() === '') && ($this->allow_empty() === false)) {
            return false;
        } else {
            return true;
        }
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
        // Multiple strings are always stored as TEXT because they can hold multiple values
        if ($this->multiple()) {
            return 'TEXT';
        }

        $max_length = $this->max_length();
        // VARCHAR or TEXT, depending on length
        if ($max_length <= 255 && $max_length != 0) {
            return 'VARCHAR('.$max_length.')';
        } else {
            return 'TEXT';
        }
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

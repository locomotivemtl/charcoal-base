<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \InvalidArgumentException as InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO as PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Core\StringFormat as StringFormat;
use \Charcoal\Property\AbstractProperty as AbstractProperty;

/**
* String Property
*/
class StringProperty extends AbstractProperty
{
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
    * @var string $_regexp
    */
    private $regexp;
    /**
    * @var boolean $_allow_empty
    */
    private $allow_empty;

    /**
    * @var StringFormat
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
    * @param integer $max_length
    * @throws InvalidArgumentException if the parameter is not an integer
    * @return StringProperty Chainable
    */
    public function set_max_length($max_length)
    {
        if (!is_integer($max_length)) {
            throw new InvalidArgumentException('Max length must be an integer.');
        }
        if ($max_length < 0) {
            throw new InvalidArgumentException('Max length must be a positive integer (>=0)');
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
    * @param integer $min_length
    * @throws InvalidArgumentException if the parameter is not an integer
    * @return StringProperty Chainable
    */
    public function set_min_length($min_length)
    {
        if (!is_integer($min_length)) {
            throw new InvalidArgumentException('Min length must be an integer.');
        }
        if ($min_length < 0) {
            throw new InvalidArgumentException('Min length must be a positive integer (>=0)');
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
    * @param string $regexp
    * @throws InvalidArgumentException if the parameter is not a string
    * @return StringProperty Chainable
    */
    public function set_regexp($regexp)
    {
        if (!is_string($regexp)) {
            throw new InvalidArgumentException('Regular expression must be a string.');
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
    * @param bool $allow_empty
    * @throws InvalidArgumentException If parameter is invalid
    * @return StringProperty Chainable
    */
    public function set_allow_empty($allow_empty)
    {
        if (!is_bool($allow_empty)) {
            throw new InvalidArgumentException('Allow empty must be a boolean');
        }
        $this->allow_empty = $allow_empty;
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
    * @throws \Exception if val is not a string
    * @return integer
    */
    public function length()
    {
        $val = $this->val();
        if (!is_string($val)) {
            throw new \Exception('Val is not a string');
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

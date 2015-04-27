<?php

namespace Charcoal\Property;

use \Charcoal\Model\Property as Property;
use \Charcoal\Model\Validator\Propertyalidator as Validator;

/**
*
*/
class StringProperty extends Property
{
    const DEFAULT_MIN_LENGTH = 0;
    const DEFAULT_MAX_LENGTH = 255;
    const DEFAULT_REGEXP = '';
    const DEFAULT_ALLOW_EMPTY = true;

    /**
    * @var int $_min_length
    */
    private $_min_length;
    /**
    * @var int $_max_length
    */
    private $_max_length;
    /**
    * @var string $_regexp
    */
    private $_regexp;
    /**
    * @var boolean $_allow_empty
    */
    private $_allow_empty;

    /**
    * @param array $data
    * @throws \InvalidArgumentException if the parameter is not an array
    * @return StringProperty Chainable
    */
    public function set_data($data)
    {

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data must be an array');
        }

        parent::set_data($data);

        if (isset($data['max_length']) && $data['max_length'] !== null) {
            $this->set_max_length($data['max_length']);
        }
        if (isset($data['min_length']) && $data['min_length'] !== null) {
            $this->set_min_length($data['min_length']);
        }
        if (isset($data['regexp']) && $data['regexp'] !== null) {
            $this->set_regexp($data['regexp']);
        }
        if (isset($data['allow_empty']) && $data['allow_empty'] !== null) {
            $this->set_allow_empty($data['allow_empty']);
        }
        return $this;
    }

    /**
    * @param integer $max_length
    * @throws \InvalidArgumentException if the parameter is not an integer
    * @return StringProperty Chainable
    */
    public function set_max_length($max_length)
    {
        if (!is_integer($max_length)) {
            throw new \InvalidArgumentException("Max length must be an integer.");
        }
        if ($max_length < 0) {
            throw new \InvalidArgumentException("Max length must be a positive integer (>=0)");
        }
        $this->_max_length = $max_length;
        return $this;
    }

    /**
    * @return integer
    */
    public function max_length()
    {
        if ($this->_max_length === null) {
            $this->_max_length = self::DEFAULT_MAX_LENGTH;
        }
        return $this->_max_length;
    }

    /**
    * @param integer $min_length
    * @throws \InvalidArgumentException if the parameter is not an integer
    * @return StringProperty Chainable
    */
    public function set_min_length($min_length)
    {
        if (!is_integer($min_length)) {
            throw new \InvalidArgumentException("Min length must be an integer.");
        }
        if ($min_length < 0) {
            throw new \InvalidArgumentException("Min length must be a positive integer (>=0)");
        }
        $this->_min_length = $min_length;
        return $this;
    }

    /**
    * @return integer
    */
    public function min_length()
    {
        if ($this->_min_length === null) {
            $this->_min_length = self::DEFAULT_MIN_LENGTH;
        }
        return $this->_min_length;
    }

    /**
    * @param string $regexp
    * @throws \InvalidArgumentException if the parameter is not a string
    * @return StringProperty Chainable
    */
    public function set_regexp($regexp)
    {
        if (!is_string($regexp)) {
            throw new \InvalidArgumentException("Regular expression must be a string.");
        }
        $this->_regexp = $regexp;
        return $this;
    }

    /**
    * @return string
    */
    public function regexp()
    {
        if ($this->_regexp === null) {
            $this->_regexp = self::DEFAULT_REGEXP;
        }
        return $this->_regexp;
    }

    /**
    * @param bool $allow_empty
    * @throws \InvalidArgumentException If parameter is invalid
    * @return StringProperty Chainable
    */
    public function set_allow_empty($allow_empty)
    {
        if (!is_bool($allow_empty)) {
            throw new \InvalidArgumentException('Allow empty must be a boolean');
        }
        $this->_allow_empty = $allow_empty;
        return $this;
    }

    /**
    * @return bool
    */
    public function allow_empty()
    {
        if ($this->_allow_empty === null) {
            $this->_allow_empty = self::DEFAULT_ALLOW_EMPTY;
        }
        return $this->_allow_empty;
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
    * Performs all the string-related validation functions at once
    * @return boolean
    */
    public function validate_string()
    {
        $max_length = $this->validate_max_length();
        $min_length = $this->validate_min_length();
        $regexp = $this->validate_regexp();
        $allow_empty = $htis->validate_allow_empty();

        return ($max_length && $min_length && $regexp && $allow_empty);
    }

    /**
    * @return boolean
    */
    public function validate_max_length()
    {
        $val = $this->val();
        $max_length = $this->max_length();
        if ($max_length == 0) {
            return true;
        }
        
        $valid = (mb_strlen($val) <= $max_length);
        if (!$valid) {
            $this->validator()->error('Max length error');
        }

        return $valid;

    }

    /**
    * @return boolean
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
            $this->validator()->error('Min length error');
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
            $this->validator()->error('Regexp error');
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
}

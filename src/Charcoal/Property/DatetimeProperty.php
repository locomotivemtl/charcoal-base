<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \DateTime as DateTime;
use \Exception as Exception;
use \InvalidArgumentException as InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO as PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty as AbstractProperty;

/**
* Datetime Property
*/
class DatetimeProperty extends AbstractProperty
{
    const DEFAULT_MIN = null;
    const DEFAULT_MAX = null;
    const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    /**
    * @var DateTime $_min
    */
    private $_min = null;
    /**
    * @var DateTime $_max
    */
    private $_max = null;
    /**
    * @var string $_format
    */
    private $_format = 'Y-m-d H:i:s';

    /**
    * @return string
    */
    public function type()
    {
        return 'datetime';
    }

    /**
    * @param array $data
    * @return DateTimeProperty Chainable
    */
    public function set_data(array $data)
    {
        parent::set_data($data);
        if (isset($data['min']) && $data['min'] !== null) {
            $this->set_min($data['min']);
        }
        if (isset($data['max']) && $data['max'] !== null) {
            $this->set_max($data['max']);
        }
        if (isset($data['format']) && $data['format'] !== null) {
            $this->set_format($data['format']);
        }
        return $this;
    }

    /**
    * AbstractProperty > set_val(). Ensure `DateTime` object in val.
    *
    * @param string|DateTime $val
    * @throws InvalidArgumentException
    * @return DateTimeProperty Chainable
    */
    public function set_val($val)
    {
        if (is_string($val)) {
            $val = new DateTime($val);
        }
        if ($val == '') {
            return $this;
        }
        if (!($val instanceof DateTime)) {
            throw new InvalidArgumentException('Val must be a valid date');
        }
        $this->_val = $val;
        return $this;
    }

    /**
    * AbstractProperty > storage_val(). Convert `DateTime` to SQL-friendly string.
    *
    * @param string|DateTime $val Optional value to convert to storage format
    * @throws Exception if the datetime is invalid
    * @return string|null
    */
    public function storage_val($val = null)
    {
        if ($val === null) {
            $val = $this->val();
        }
        if ($val instanceof DateTime) {
            return $this->_val->format('Y-m-d H-i-s');
        } else {
            if ($this->allow_null()) {
                return null;
            } else {
                throw new Exception('Invalid datetime value');
            }
        }
    }

    /**
    * @param string|Datetime $min
    * @throws InvalidArgumentException
    * @return DatetimeProperty Chainable
    */
    public function set_min($min)
    {
        if (is_string($min)) {
            $min = new DateTime($min);
        }
        if (!($max instanceof DateTime)) {
            throw new InvalidArgumentException('Invalid min');
        }
        $this->_min = $min;
        return $this;
    }

    /**
    * @return DateTime
    */
    public function min()
    {
        if ($this->_min === null) {
            $this->_min = self::DEFAULT_MIN;
        }
        return $this->_min;
    }

    /**
    * @param string|Datetime $max
    * @throws InvalidArgumentException
    * @return DatetimeProperty Chainable
    */
    public function set_max($max)
    {
        if (is_string($max)) {
            $max = new DateTime($max);
        }
        if (!($max instanceof DateTime)) {
            throw new InvalidArgumentException('Invalid max');
        }
        $this->_max = $max;
        return $this;
    }

    /**
    * @return Datetime
    */
    public function max()
    {
        if ($this->_max === null) {
            $this->_max = self::DEFAULT_MAX;
        }
        return $this->_max;
    }

    /**
    * @param string $format
    * @throws InvalidArgumentException
    * @return DatetimeProperty Chainable
    */
    public function set_format($format)
    {
        if (!is_string($format)) {
            throw new InvalidArgumentException('Format must be a string');
        }
        $this->_format = $format;
        return $this;
    }

    /**
    * @return string
    */
    public function format()
    {
        if ($this->_format === null) {
            $this->_format = self::DEFAULT_FORMAT;
        }
        return $this->_format;
    }

    /**
    * @return mixed
    */
    public function save()
    {
        return $this->val();
    }

    /**
    * @return array
    */
    public function validation_methods()
    {
        $parent_methods = parent::validation_methods();
        return array_merge($parent_methods, ['min', 'max']);
    }

    /**
    * @return boolean
    */
    public function validate_min()
    {
        $min = $this->min();
        if (!$min) {
            return true;
        }
        $valid = ($this->val() >= $min);
        if ($valid === false) {
            $this->validator()->error('The date is smaller than the minimum value', 'min');
        }
        return $valid;
    }

    /**
    * @return boolean
    */
    public function validate_max()
    {
        $max = $this->max();
        if (!$max) {
            return true;
        }
        $valid = ($this->val() <= $max);
        if ($valid === false) {
            $this->validator()->error('The date is bigger than the maximum value', 'max');
        }
        return $valid;
    }

    /**
    * @return string
    */
    public function sql_extra()
    {
        return '';
    }

    /**
    * @return string
    */
    public function sql_type()
    {
        return 'DATETIME';
    }

    /**
    * @return integer
    */
    public function sql_pdo_type()
    {
        return PDO::PARAM_STR;
    }
}

<?php

namespace Charcoal\Property;

// Global namespaces
use \DateTime as DateTime;
use \Exception as Exception;
use \InvalidArgumentException as InvalidArgumentException;
use \PDO as PDO;

// In `charcoal-core`
use \Charcoal\Property\AbstractProperty as AbstractProperty;

class DatetimeProperty extends AbstractProperty
{
    const DEFAULT_MIN = 0;
    const DEFAULT_MAX = 0;
    const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    /**
    * @var DateTime $_min
    */
    private $_min;
    /**
    * @var DateTime $_max
    */
    private $_max;
    /**
    * @var string $_format
    */
    private $_format;

    /**
    * @param array $data
    * @throws InvalidArgumentException
    * @return DateTimeProperty Chainable
    */
    public function set_data($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Data must be an array');
        }

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
        if (!($val instanceof DateTime)) {
            throw new InvalidArgumentException('Val must be a valid date');
        }
        $this->_val = $val;
        return $this;
    }

    /**
    * AbstractProperty > storage_val(). Convert `DateTime` to SQL-friendly string.
    *
    * @param string|DateTime Optional value to convert to storage format
    * @throws Exception if the datetime is invalid
    * @return string|null
    */
    public function storage_val($val=null)
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

    public function min()
    {
        if ($this->_min === null) {
            $this->_min = self::DEFAULT_MIN;
        }
        return $this->_min;
    }

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

    public function max()
    {
        if ($this->_max === null) {
            $this->_max = self::DEFAULT_MAX;
        }
        return $this->_max;
    }

    public function set_format($format)
    {
        if (!is_string($format)) {
            throw new InvalidArgumentException('Format must be a string');
        }
        $this->_format = $format;
        return $this;
    }

    public function format()
    {
        if ($this->_format === null) {
            $this->_format = self::DEFAULT_FORMAT;
        }
        return $this->_format;
    }

    public function validate_datetime()
    {
        $min = $this->validate_min();
        $max = $this->validate_max();

        return ($min && $max);
    }

    public function validate_min()
    {
        $min = $this->min();
        if (!$min) {
            return true;
        }
        return ($this->val() >= $min);
    }

    public function validate_max()
    {
        $max = $this->max();
        if (!$max) {
            return true;
        }
        return ($this->val() <= $max);
    }

    public function sql_type()
    {
        return 'DATETIME';
    }

    public function sql_pdo_type()
    {
        return PDO::PARAM_STR;
    }
}

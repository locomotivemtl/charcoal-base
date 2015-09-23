<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \DateTime;
use \DateTimeInterface;
use \Exception;
use \InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty;

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
    private $min = null;
    /**
    * @var DateTime $_max
    */
    private $max = null;
    /**
    * @var string $_format
    */
    private $format = 'Y-m-d H:i:s';

    /**
    * @return string
    */
    public function type()
    {
        return 'datetime';
    }

    /**
    * AbstractProperty > set_multiple()
    *
    * Ensure multiple can not be true for Datetime property.
    *
    * @param boolean $multiple
    * @throws InvalidArgumentException If the multiple argument is true (must be false)
    * @return DatetimeProperty Chainable
    */
    public function set_multiple($multiple)
    {
        $multiple = !!$multiple;
        if ($multiple === true) {
            throw new InvalidArgumentException(
                'Multiple can not be true for datetime property.'
            );
        }
        return $this;
    }

    /**
    * AbstractProperty > multiple()
    *
    * Multiple is always false for Date property.
    *
    * @return boolean
    */
    public function multiple()
    {
        return false;
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
        if ($val === null) {
            if ($this->allow_null()) {
                $this->val = null;
                return $this;
            } else {
                throw new InvalidArgumentException(
                    'Val can not be null (Not allowed)'
                );
            }
        }
        if (is_string($val)) {
            $val = new DateTime($val);
        }
        if (!($val instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Val must be a valid date'
            );
        }
        $this->val = $val;
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
        if ($val instanceof DateTimeInterface) {
            return $this->val->format('Y-m-d H:i:s');
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
        if ($min === null) {
            $this->min = null;
            return $this;
        }
        if (is_string($min)) {
            try {
                $min = new DateTime($min);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    'Can not set min: '.$e->getMessage()
                );
            }
        }
        if (!($min instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid min'
            );
        }
        $this->min = $min;
        return $this;
    }

    /**
    * @return DateTime
    */
    public function min()
    {
        if ($this->min === null) {
            $this->min = self::DEFAULT_MIN;
        }
        return $this->min;
    }

    /**
    * @param string|Datetime $max
    * @throws InvalidArgumentException
    * @return DatetimeProperty Chainable
    */
    public function set_max($max)
    {
        if ($max === null) {
            $this->max = null;
            return $this;
        }
        if (is_string($max)) {
            try {
                $max = new DateTime($max);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    'Can not set min: '.$e->getMessage()
                );
            }
        }
        if (!($max instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid max'
            );
        }
        $this->max = $max;
        return $this;
    }

    /**
    * @return Datetime
    */
    public function max()
    {
        if ($this->max === null) {
            $this->max = self::DEFAULT_MAX;
        }
        return $this->max;
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
        $this->format = $format;
        return $this;
    }

    /**
    * @return string
    */
    public function format()
    {
        if ($this->format === null) {
            $this->format = self::DEFAULT_FORMAT;
        }
        return $this->format;
    }

    /**
    * @return mixed
    */
    public function save()
    {
        return $this->val();
    }

    /**
    * Format `DateTime` to string.
    *
    * @todo   Adapt for l10n
    * @return string|null
    */
    public function display_val($val = null)
    {
        if ($val !== null) {
            $this->set_val($val);
        }
        
        if ($this->val()) {
            return $this->val()->format($this->format());
        }
        return '';
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

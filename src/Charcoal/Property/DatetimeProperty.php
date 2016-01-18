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
     * @var DateTime $min
     */
    private $min = null;
    /**
     * @var DateTime $max
     */
    private $max = null;
    /**
     * @var string $format
     */
    private $format = self::DEFAULT_FORMAT;

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
     * @param boolean $multiple Multiple flag.
     * @throws InvalidArgumentException If the multiple argument is true (must be false).
     * @return DatetimeProperty Chainable
     */
    public function setMultiple($multiple)
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
     * AbstractProperty > setVal(). Ensure `DateTime` object in val.
     *
     * @param string|DateTime $val The value to set.
     * @throws InvalidArgumentException If the value is invalid.
     * @return DateTimeProperty Chainable
     */
    public function setVal($val)
    {
        if ($val === null) {
            if ($this->allowNull()) {
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
     * AbstractProperty > storageVal(). Convert `DateTime` to SQL-friendly string.
     *
     * @param string|DateTime $val Optional. Value to convert to storage format.
     * @throws Exception If the datetime is invalid.
     * @return string|null
     */
    public function storageVal($val = null)
    {
        if ($val === null) {
            $val = $this->val();
        }
        if ($val instanceof DateTimeInterface) {
            return $this->val->format('Y-m-d H:i:s');
        } else {
            if ($this->allowNull()) {
                return null;
            } else {
                throw new Exception('Invalid datetime value');
            }
        }
    }

    /**
     * Format `DateTime` to string.
     *
     * > Warning: Passing a value as a parameter sets this value in the objects (calls setVal())
     *
     * @param mixed $val Optional.
     * @todo   Adapt for l10n
     * @return string|null
     */
    public function displayVal($val = null)
    {
        if ($val !== null) {
            $this->setVal($val);
        }

        if ($this->val()) {
            return $this->val()->format($this->format());
        }
        return '';
    }

    /**
     * @param string|Datetime|null $min The minimum allowed value.
     * @throws InvalidArgumentException If the datetime is invalid.
     * @return DatetimeProperty Chainable
     */
    public function setMin($min)
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
     * @param string|Datetime|null $max The maximum allowed value.
     * @throws InvalidArgumentException If the datetime is invalid.
     * @return DatetimeProperty Chainable
     */
    public function setMax($max)
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
     * @param string $format The date format.
     * @throws InvalidArgumentException If the format is not a string.
     * @return DatetimeProperty Chainable
     */
    public function setFormat($format)
    {
        if (!is_string($format)) {
            throw new InvalidArgumentException(
                'Format must be a string'
            );
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
     * @return array
     */
    public function validationMethods()
    {
        $parent_methods = parent::validationMethods();
        return array_merge($parent_methods, ['min', 'max']);
    }

    /**
     * @return boolean
     */
    public function validateMin()
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
    public function validateMax()
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
    public function sqlExtra()
    {
        return '';
    }

    /**
     * @return string
     */
    public function sqlType()
    {
        return 'DATETIME';
    }

    /**
     * @return integer
     */
    public function sqlPdoType()
    {
        return PDO::PARAM_STR;
    }

    /**
     * Json Serialize
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $val = $this->val();
        if ($val === null) {
            return null;
        }

        if ($val instanceof DateTimeInterface) {
            return $val->format(DateTime::ATOM);
        } else {
            return $val;
        }
    }
}

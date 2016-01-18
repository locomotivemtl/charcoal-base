<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO as PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty;

/**
 * Structure Property.
 *
 * Complex, structured data as array (stored as json).
 */
class StructureProperty extends AbstractProperty
{
    /**
     * @return string
     */
    public function type()
    {
        return 'structure';
    }

    /**
     * AbstractProperty > set_val(). Ensure `DateTime` object in val.
     *
     * @param string|array $val The value to set.
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
        if (!is_array($val)) {
            $val = json_decode($val, true);
        }
        $this->val = $val;
        return $this;
    }

    /**
     * @return string
     */
    public function sqlExtra()
    {
        return '';
    }

    /**
     * For a lack of better array support in mysql, data is stored as encoded JSON in a LONGTEXT.
     * @return string
     */
    public function sqlType()
    {
        return 'LONGTEXT';
    }

    /**
     * @return integer
     */
    public function sqlPdoType()
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

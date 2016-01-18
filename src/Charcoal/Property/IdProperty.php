<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty;

/**
 * ID Property
 */
class IdProperty extends AbstractProperty
{
    const MODE_AUTO_INCREMENT = 'auto-increment';
    const MODE_UNIQID = 'uniqid';
    const MODE_UUID = 'uuid';

    const DEFAULT_MODE = 'auto-increment';

    /**
     * ID mode. Can be:
     * - `auto-increment` (default)
     * - `uniq`
     * - `uuid`
     *
     * @var string $_mode
     */
    private $mode;

    /**
     * @return string
     */
    public function type()
    {
        return 'id';
    }

    /**
     * AbstractProperty > set_multiple()
     *
     * Ensure multiple can not be true for ID property.
     *
     * @param boolean $multiple The multiple flag.
     * @throws InvalidArgumentException If the multiple argument is true (must be false).
     * @return IdProperty Chainable
     */
    public function setMultiple($multiple)
    {
        $multiple = !!$multiple;
        if ($multiple === true) {
            throw new InvalidArgumentException(
                'Multiple can not be true for ID property.'
            );
        }
        return $this;
    }

    /**
     * AbstractProperty > multiple()
     *
     * Multiple is always false for ID property.
     *
     * @return boolean
     */
    public function multiple()
    {
        return false;
    }

    /**
     * @param string $mode The ID mode (auto-increment, uniqid or uuid).
     * @throws InvalidArgumentException If the mode is not one of the 3 valid modes.
     * @return IdProperty Chainable
     */
    public function setMode($mode)
    {
        $available_modes = [
            self::MODE_AUTO_INCREMENT,
            self::MODE_UNIQID,
            self::MODE_UUID
        ];
        if (!in_array($mode, $available_modes)) {
            throw new InvalidArgumentException(
                'Mode is not a valid mode.'
            );
        }
        $this->mode = $mode;
        return $this;
    }

    /**
     * @return string
     */
    public function mode()
    {
        if ($this->mode === null) {
            $this->mode = self::DEFAULT_MODE;
        }
        return $this->mode;
    }

    /**
     * Prepare the value for save
     *
     * If no ID is set upon first save, then auto-generate it if necessary
     *
     * @see Charcoal_Object::save()
     * @return mixed
     */
    public function save()
    {
        $val = $this->val();
        if (!$val) {
            $val = $this->autoGenerate();
            $this->setVal($val);
        }

        return $val;
    }

    /**
     * Auto-generate a value upon first save
     *
     * @return string
     */
    public function autoGenerate()
    {
        $mode = $this->mode();

        if ($mode == self::MODE_AUTO_INCREMENT) {
            // auto-increment is handled at the database level (for now...)
            return '';
        } elseif ($mode == self::MODE_UNIQID) {
            return \uniqid();
        } elseif ($mode == self::MODE_UUID) {
            return $this->generateUuid();
        }
    }

    /**
     * Generate a RFC-4122 v4 Universally-Unique Identifier
     *
     * @return string
     *
     * @see http://tools.ietf.org/html/rfc4122#section-4.4
     */
    private function generateUuid()
    {
        // Generate a uniq string identifer (valid v4 uuid)
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low" flag
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid" flag
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_andVersion" flat (4 most significant bits holds version number)
            (mt_rand(0, 0x0fff) | 0x4000),
            // 16 bits, 8 bits for "clk_seq_hi_res" flag and 8 bits for "clk_seq_low" flag
            // two most significant bits holds zero and one for variant DCE1.1
            (mt_rand(0, 0x3fff) | 0x8000),
            // 48 bits for "node" flag
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * @return string
     * @see AbstractProperty::fields()
     */
    public function sqlExtra()
    {
        $mode = $this->mode();
        if ($mode == self::MODE_AUTO_INCREMENT) {
            return 'AUTO_INCREMENT';
        } else {
            return '';
        }
    }

    /**
     * Get the SQL type (Storage format)
     *
     * @return string The SQL type
     * @see AbstractProperty::fields()
     */
    public function sqlType()
    {
        $mode = $this->mode();
        if ($mode == self::MODE_AUTO_INCREMENT) {
            return 'INT(10) UNSIGNED';
        } elseif ($mode == self::MODE_UNIQID) {
            return 'CHAR(13)';
        } elseif ($mode == self::MODE_UUID) {
            return 'CHAR(36)';
        }
    }

    /**
     * @return integer
     * @see AbstractProperty::fields()
     */
    public function sqlPdoType()
    {
        $mode = $this->mode();
        if ($mode == 'auto-increment') {
            return PDO::PARAM_INT;
        } else {
            return PDO::PARAM_STR;
        }
    }
}

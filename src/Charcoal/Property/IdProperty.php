<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \InvalidArgumentException as InvalidArgumentException;

// Dependencies from `PHP` extensions
use \PDO as PDO;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty as AbstractProperty;

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
    private $_mode;

    /**
    * @return string
    */
    public function type()
    {
        return 'id';
    }

    /**
    * @param array $data
    * @return IdProperty Chainable
    */
    public function set_data(array $data)
    {
        parent::set_data($data);
        if (isset($data['mode']) && $data['mode'] !== null) {
            $this->set_mode($data['mode']);
        }
        return $this;
    }

    /**
    * @param string $mode
    * @throws InvalidArgumentException
    * @return IdProperty Chainable
    */
    public function set_mode($mode)
    {
        $available_modes = [
            self::MODE_AUTO_INCREMENT,
            self::MODE_UNIQID,
            self::MODE_UUID
        ];
        if (!in_array($mode, $available_modes)) {
            throw new InvalidArgumentException('Mode is not a valid mode');
        }
        $this->_mode = $mode;
        return $this;
    }

    /**
    * @return string
    */
    public function mode()
    {
        if ($this->_mode === null) {
            $this->_mode = self::DEFAULT_MODE;
        }
        return $this->_mode;
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
            $val = $this->auto_generate();
            $this->set_val($val);
        }
        
        return $val;
    }

    /**
    * Auto-generate a value upon first save
    *
    * @return string
    */
    public function auto_generate()
    {
        $mode = $this->mode();

        if ($mode == self::MODE_AUTO_INCREMENT) {
            // auto-increment is handled at the database level (for now...)
            return '';
        } elseif ($mode == self::MODE_UNIQID) {
            return \uniqid();
        } elseif ($mode == self::MODE_UUID) {
            return $this->_generate_uuid();
        }
    }

    /**
    * Generate a RFC-4122 v4 Universally-Unique Identifier
    *
    * @return string
    *
    * @see http://tools.ietf.org/html/rfc4122#section-4.4
    */
    private function _generate_uuid()
    {
        // Generate a uniq string identifer (valid v4 uuid)
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            (mt_rand(0, 0x0fff) | 0x4000),
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (mt_rand(0, 0x3fff) | 0x8000),
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
    * @return string
    * @see AbstractProperty::fields()
    */
    public function sql_extra()
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
    public function sql_type()
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
    public function sql_pdo_type()
    {
        $mode = $this->mode();
        if ($mode == 'auto-increment') {
            return PDO::PARAM_INT;
        } else {
            return PDO::PARAM_STR;
        }
    }
}

<?php

namespace Charcoal\Property;

use \Exception as Exception;
use \InvalidArgumentException as InvalidArgumentException;

// In charcoal-core
use \Charcoal\Property\AbstractProperty as AbstractProperty;

class ObjectProperty extends AbstractProperty
{
    /**
    * @var string $_obj_type
    */
    private $_obj_type;

    /**
    * @return string
    */
    public function type()
    {
        return 'object';
    }

    /**
    * @param array $data
    * @return ObjectProperty Chainable
    */
    public function set_data(array $data)
    {
        parent::set_data($data);

        if (isset($data['obj_type']) && $data['obj_type'] !== null) {
            $this->set_obj_type($data['obj_type']);
        }

        return $this;
    }

    /**
    * @param string $obj_type
    * @throws InvalidArgumentException
    * @return ObjectPropertyChainable
    */
    public function set_obj_type($obj_type)
    {
        if (!is_string($obj_type)) {
            throw new InvalidArgumentException('Obj type needs to be a string');
        }
        $this->_obj_type = $obj_type;
        return $this;
    }

    public function obj_type()
    {
        if (!$this->_obj_type === null) {
            throw new Exception('No obj type defined. Invalid property.');
        }
        return $this->_obj_type;
    }

    public function sql_extra()
    {
        return '';
    }

    public function sql_type()
    {
        // @todo
        return 'VARCHAR(255)';
    }

    public function sql_pdo_type()
    {
        // @tdo
        return \PDO::PARAM_STR;
    }

    public function save()
    {
        return $this->val();
    }
}

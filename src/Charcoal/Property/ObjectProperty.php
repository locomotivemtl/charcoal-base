<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \Exception as Exception;
use \InvalidArgumentException as InvalidArgumentException;

// Module `charcoal-core` dependencies
use \Charcoal\Property\AbstractProperty as AbstractProperty;
use \Charcoal\Model\ModelFactory;
use \Charcoal\Loader\CollectionLoader;

/**
* Object Property
*/
class ObjectProperty extends AbstractProperty
{
    /**
    * @var string $_obj_type
    */
    private $obj_type;

    /**
    * @return string
    */
    public function type()
    {
        return 'object';
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
        $this->obj_type = $obj_type;
        return $this;
    }

    /**
    * @throws Exception
    * @return string
    */
    public function obj_type()
    {
        if (!$this->obj_type === null) {
            throw new Exception('No obj type defined. Invalid property.');
        }
        return $this->obj_type;
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
        if ($this->multiple() === true) {
            return 'TEXT';
        } else {
            // Read from proto's key
            $proto = $this->proto();
            $key = $proto->p($proto->key());
            return $key->sql_type();
        }
    }

    /**
    * @return integer
    */
    public function sql_pdo_type()
    {
        // Read from proto's key
        $proto = $this->proto();
        $key = $proto->p($proto->key());
        return $key->sql_pdo_type();
    }

    /**
    * @return mixed
    */
    public function save()
    {
        return $this->val();
    }

    public function proto()
    {
        return ModelFactory::instance()->get($this->obj_type());
    }

    /**
    * Get the choices form Model Collection
    */
    public function choices()
    {
        $proto = $this->proto();
        $loader = new CollectionLoader();
        $loader->set_model($this->proto());

        if ($proto->has_property('active')) {
            $loader->add_filter('active', true);
        }
        $choices = $loader->load();
        foreach ($choices as $c) {
            $choice = [
                'value'=>$c->id(),
                'label'=>'Label '.$c->name()->fr(),
                'title'=>'Title '.$c->name()->fr(),
                'subtext'=>'',
                'icon'=>''
            ];

            yield $choice;
        }
    }
}

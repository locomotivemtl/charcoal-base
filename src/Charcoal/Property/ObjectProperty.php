<?php

namespace Charcoal\Property;

// Dependencies from `PHP`
use \Exception;
use \InvalidArgumentException;

// Module `charcoal-core` dependencies
use \Charcoal\Charcoal;
use \Charcoal\Property\AbstractProperty;
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

    /**
    * @return ModelInterface
    */
    public function proto()
    {
        $factory = new ModelFactory();
        return $factory->get($this->obj_type(), [
            'logger'=>Charcoal::logger()
        ]);
    }

    public function display_val($val = null)
    {
        if ($val === null) {
            $val = $this->val();
        }

        if ($val === null) {
            return '';
        }

        $property_value = $val;

        if ($this->l10n() === true) {
            $translator = TranslationConfig::instance();

            $property_value = $property_value[$translator->current_language()];
        }

        if ($this->multiple() === true) {
            if (!is_array($property_value)) {
                $property_value = explode($this->multiple_separator(), $property_value);
            }
        }
        else {
            $property_value = [$property_value];
        }

        $names = [];
        foreach($property_value as $p) {
            $proto = $this->proto();
            $proto->load($p);
            $names[] = (string)$proto->name();
        }
        return implode(', ', $names);
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
                'label'=>$c->name(),
                'title'=>$c->name(),
                'subtext'=>'',
                'icon'=>'',
                'selected'=>$this->is_choice_selected($c->id())
            ];

            yield $choice;
        }
    }

    /**
    * @return boolean
    */
    public function is_choice_selected($c)
    {
        $val = $this->val();
        if ($val === null) {
            return false;
        }
        if ($this->multiple()) {
            return in_array($c, $val);
        } else {
            return $c == $val;
        }
    }
}

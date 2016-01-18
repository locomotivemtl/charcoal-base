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

// Local namespace dependencies
use \Charcoal\Property\SelectablePropertyInterface;

/**
 * Object Property holds a reference to an external object.
 *
 * The object property implements the full `SelectablePropertyInterface` without using
 * its accompanying trait. (`set_choices`, `add_choice`, `choices`, `has_choice`, `choice`).
 */
class ObjectProperty extends AbstractProperty implements SelectablePropertyInterface
{
    /**
     * @var string $ObjType
     */
    private $objType;

    /**
     * @var ModelFactory $modelFactory
     */
    private $modelFactory;

    /**
     * The available selectable choices map.
     *
     * @var array The internal choices
     */
    protected $choices = [];

    /**
     * @return string
     */
    public function type()
    {
        return 'object';
    }

    /**
     * @return ModelFactory
     */
    private function modelFactory()
    {
        if ($this->modelFactory === null) {
            $this->modelFactory = new ModelFactory();
        }
        return $this->modelFactory;
    }

    /**
     * @param string $objType The object type.
     * @throws InvalidArgumentException If the object type is not a string.
     * @return ObjectPropertyChainable
     */
    public function setObjType($objType)
    {
        if (!is_string($objType)) {
            throw new InvalidArgumentException(
                'Obj type needs to be a string'
            );
        }
        $this->objType = $objType;
        return $this;
    }

    /**
     * @throws Exception If the object type was not previously set.
     * @return string
     */
    public function objType()
    {
        if (!$this->objType === null) {
            throw new Exception(
                'No obj type defined. Invalid property.'
            );
        }
        return $this->objType;
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
        if ($this->multiple() === true) {
            return 'TEXT';
        } else {
            // Read from proto's key
            $proto = $this->proto();
            $key = $proto->p($proto->key());
            return $key->sqlType();
        }
    }

    /**
     * @return integer
     */
    public function sqlPdoType()
    {
        // Read from proto's key
        $proto = $this->proto();
        $key = $proto->p($proto->key());
        return $key->sqlPdoType();
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
        return $this->modelFactory()->get($this->objType(), [
            'logger' => $this->logger
        ]);
    }

    /**
     * @param mixed $val Optional. The value to display.
     * @return string
     */
    public function displayVal($val = null)
    {
        if ($val === null) {
            $val = $this->val();
        }

        if ($val === null) {
            return '';
        }

        $propertyValue = $val;

        if ($this->l10n() === true) {
            $translator = TranslationConfig::instance();

            $propertyValue = $propertyValue[$translator->current_language()];
        }

        if ($this->multiple() === true) {
            if (!is_array($propertyValue)) {
                $propertyValue = explode($this->multipleSeparator(), $propertyValue);
            }
        } else {
            $propertyValue = [$propertyValue];
        }

        $names = [];
        foreach ($propertyValue as $p) {
            $proto = $this->proto();
            $proto->load($p);
            $names[] = (string)$proto->name();
        }
        return implode(', ', $names);
    }

    /**
     * Fulfills the SelectableProperty interface, but does nothing.
     *
     * @param array $choices The array of choice structures.
     * @return SelectablePropertyInterface Chainable.
     */
    public function setChoices(array $choices)
    {
        unset($choices);
        $this->logger->debug('Choices can not be set for object properties. They are auto-generated from objects.');
        return $this;
    }

    /**
     * Add a choice to the available choices map.
     *
     * @param string $choice_ident The choice identifier (will be key / default ident).
     * @param array  $choice       A choice structure.
     * @return SelectablePropertyInterface Chainable.
     */
    public function addChoice($choice_ident, array $choice)
    {
        unset($choice_ident, $choice);
        $this->logger->debug('Choices can not be added for object properties. They are auto-generated from objects.');
        return $this;
    }

    /**
     * Get the choices array map.
     *
     * @return array
     */
    public function choices()
    {
        $proto = $this->proto();
        $loader = new CollectionLoader();
        $loader->set_model($this->proto());

        if ($proto->has_property('active')) {
            $loader->add_filter('active', true);
        }
        $ret = [];
        $choices = $loader->load();
        foreach ($choices as $c) {
            $choice = [
                'value'=>$c->id(),
                'label'=>$c->name(),
                'title'=>$c->name(),
                'subtext'=>'',
                'icon'=>$c->icon()
            ];

            $ret[$c->id()] = $choice;
        }

        return $ret;
    }

    /**
     * Returns wether a given choice_ident exists or not.
     *
     * @param string $choice_ident The choice identifier.
     * @return boolean True / false wether the choice exists or not.
     */
    public function hasChoice($choice_ident)
    {
        $c = $this->modelFactory()->create($this->objType(), [
            'logger'=>$this->logger
        ]);
        $c->load($choice_ident);
        return ($c->id() == $choice_ident);
    }

    /**
     * Returns a choice structure for a given ident.
     *
     * @param string $choice_ident The choice ident to load.
     * @return mixed The matching choice.
     */
    public function choice($choice_ident)
    {
        $c = $this->modelFactory()->create($this->objType(), [
            'logger'=>$this->logger
        ]);
        $c->load($choice_ident);

        $choice = [
            'value'=>$c->id(),
            'label'=>$c->name(),
            'title'=>$c->name(),
            'subtext'=>'',
            'icon'=>$c->icon()
        ];

        return $choice;
    }
}

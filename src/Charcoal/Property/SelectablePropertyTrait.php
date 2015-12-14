<?php

namespace Charcoal\Property;

trait SelectablePropertyTrait
{
    /**
    * The available selectable choices map.
    *
    * @var array The internal choices
    */
    protected $choices = [];

    /**
    * Explicitely set the selectable choices (to the array map).
    *
    * @param array $choices The array of choice structures.
    * @return SelectablePropertyInterface Chainable.
    */
    public function set_choices(array $choices)
    {
        $this->choices = [];
        foreach ($choices as $choice_ident => $choice) {
            $c = (string)$choice_ident;
            $this->add_choice($c, $choice);
        }
        return $this;
    }

    /**
    * Add a choice to the available choices map.
    *
    * @param string The choice identifier (will be key / default ident).
    * @param array A choice structure.
    * @throws InvalidArgumentException If the choice ident is not a string.
    * @return SelectablePropertyInterface Chainable.
    */
    public function add_choice($choice_ident, array $choice)
    {
        if (!is_string($choice_ident)) {
            throw new InvalidArgumentException(
                'Choice identifier must be a string.'
            );
        }
        $this->choices[$choice_ident] = $choice;
        return $this;
    }

    /**
    * Get the choices array map.
    *
    * @return array
    */
    public function choices()
    {
        return $this->choices;
    }

    /**
    * Returns wether a given choice_ident exists or not.
    *
    * @param string $choice_ident
    * @return boolean True / false wether the choice exists or not.
    */
    public function has_choice($choice_ident)
    {
        return isset($this->choices[$choice_ident]);
    }

    /**
    * Returns a choice structure for a given ident.
    *
    * @param string $choice_ident The choice ident to load.
    * @return mixed The matching choice.
    */
    public function choice($choice_ident)
    {
        return $this->choices[$choice_ident];
    }
}

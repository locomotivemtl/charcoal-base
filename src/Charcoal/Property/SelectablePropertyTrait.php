<?php

namespace Charcoal\Property;

/**
*
*/
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
    public function setChoices(array $choices)
    {
        $this->choices = [];
        foreach ($choices as $choiceIdent => $choice) {
            $c = (string)$choiceIdent;
            $this->addChoice($c, $choice);
        }
        return $this;
    }

    /**
     * Add a choice to the available choices map.
     *
     * @param string $choiceIdent The choice identifier (will be key / default ident).
     * @param array  $choice      A choice structure.
     * @throws InvalidArgumentException If the choice ident is not a string.
     * @return SelectablePropertyInterface Chainable.
     */
    public function addChoice($choiceIdent, array $choice)
    {
        if (!is_string($choiceIdent)) {
            throw new InvalidArgumentException(
                'Choice identifier must be a string.'
            );
        }
        $this->choices[$choiceIdent] = $choice;
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
     * Returns wether a given choiceIdent exists or not.
     *
     * @param string $choiceIdent The choice ident.
     * @return boolean True / false wether the choice exists or not.
     */
    public function hasChoice($choiceIdent)
    {
        return isset($this->choices[$choiceIdent]);
    }

    /**
     * Returns a choice structure for a given ident.
     *
     * @param string $choiceIdent The choice ident to load.
     * @return mixed The matching choice.
     */
    public function choice($choiceIdent)
    {
        return $this->choices[$choiceIdent];
    }
}

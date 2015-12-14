<?php

namespace Charcoal\Property;

/**
* Selectable properties provide choices.
*
* Choices are :
* - `value`
* - `label`
* - `title`
* - `subtext`
* - `icon`
* - `selected`
*/
interface SelectablePropertyInterface
{
    /**
    * Explicitely set the selectable choices (to the array map).
    *
    * @param array $choices The array of choice structures.
    * @return SelectablePropertyInterface Chainable.
    */
    public function set_choices(array $choices);

    /**
    * Add a choice to the available choices map.
    *
    * @param string The choice identifier (will be key / default ident).
    * @param array A choice structure.
    * @return SelectablePropertyInterface Chainable.
    */
    public function add_choice($choice_ident, array $choice);

    /**
    * Get the choices array map.
    *
    * @return array
    */
    public function choices();

    /**
    * Returns wether a given choice_ident exists or not.
    *
    * @param string $choice_ident
    * @return boolean True / false wether the choice exists or not.
    */
    public function has_choice($choice_ident);

    /**
    * Returns a choice structure for a given ident.
    *
    * @param string $choice_ident The choice ident to load.
    * @return mixed The matching choice.
    */
    public function choice($choice_ident);
}

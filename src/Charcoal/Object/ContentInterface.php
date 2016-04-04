<?php

namespace Charcoal\Object;

/**
 * The `Content` Object
 *
 * _Content_ objects are models with identity and typically created
 * by the application's manager.
 *
 * Examples of _Content_ objects: _Section_ or _Page_, _News Article_,
 * _Blog Entry_, _Media Gallery_, _Survey_, _Product_, _FAQ_, etc.
 */
interface ContentInterface
{
    /**
     * Whether a new object should be enabled or disabled.
     */
    const ACTIVE_BY_DEFAULT = true;

    /**
     * Set whether the object is enabled or disabled.
     *
     * @param  boolean $active The active flag.
     * @return Content Chainable
     */
    public function setActive($active);

    /**
     * Determine if the object is enabled or disabled.
     *
     * @return boolean
     */
    public function active();

    /**
     * Set the object's position.
     *
     * @param  integer $position The position (for ordering purpose).
     * @return Content Chainable
     */
    public function setPosition($position);

    /**
     * Retrieve the object's position.
     *
     * @return integer
     */
    public function position();
}

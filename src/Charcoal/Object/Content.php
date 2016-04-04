<?php

namespace Charcoal\Object;

use \InvalidArgumentException;

// Dependencies from 'charcoal-core'
use \Charcoal\Model\AbstractModel;
use \Charcoal\Core\IndexableInterface;
use \Charcoal\Core\IndexableTrait;

// Local dependencies
use \Charcoal\Object\ContentInterface;
use \Charcoal\Object\RevisionableInterface;
use \Charcoal\Object\RevisionableTrait;
use \Charcoal\Object\TimestampableInterface;
use \Charcoal\Object\TimestampableTrait;

/**
 * The `Content` Object
 *
 * _Content_ objects are models with identity and typically created
 * by the application's manager.
 */
class Content extends AbstractModel implements
    ContentInterface,
    IndexableInterface,
    RevisionableInterface,
    TimestampableInterface
{
    use IndexableTrait;
    use RevisionableTrait;
    use TimestampableTrait;

    /**
     * Whether the object is enabled or disabled.
     *
     * An active object is considered publicly queryable.
     *
     * @var boolean
     */
    private $active = self::ACTIVE_BY_DEFAULT;

    /**
     * The object's position.
     *
     * Used for ordering the object in lists.
     *
     * @var integer
     */
    private $position = 0;

    /**
     * Set whether the object is enabled or disabled.
     *
     * @param  boolean $active The active flag.
     * @return Content Chainable
     */
    public function setActive($active)
    {
        $this->active = !!$active;

        return $this;
    }

    /**
     * Determine if the object is enabled or disabled.
     *
     * @return boolean
     */
    public function active()
    {
        return $this->active;
    }

    /**
     * Set the object's position.
     *
     * @param  integer $position The position (for ordering purpose).
     * @throws InvalidArgumentException If the position is not an integer (or numeric integer string).
     * @return Content Chainable
     */
    public function setPosition($position)
    {
        if ($position === false) {
            $position = null;
        }

        if ($position !== null) {
            if (!is_numeric($position)) {
                throw new InvalidArgumentException(
                    'Position must be an integer.'
                );
            }

            $position = (int)$position;
        }

        $this->position = $position;

        return $this;
    }

    /**
     * Retrieve the object's position.
     *
     * @return integer
     */
    public function position()
    {
        return $this->position;
    }

    /**
     * Event called before _creating_ the object.
     *
     * @see    StorableTrait::preSave() For the "create" Event.
     * @return boolean
     */
    public function preSave()
    {
        $result = parent::preSave();

        $this->createTimestampable();

        return $result;
    }

    /**
     * Event called before _updating_ the object.
     *
     * @see    StorableTrait::preUpdate() For the "update" Event.
     * @return boolean
     */
    public function preUpdate(array $properties = null)
    {
        $result = parent::preUpdate($properties);

        if ($this->revisionEnabled()) {
            $this->generateRevision();
        }

        $this->updateTimestampable();

        return $result;
    }
}

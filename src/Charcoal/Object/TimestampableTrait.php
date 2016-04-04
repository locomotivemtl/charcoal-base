<?php

namespace Charcoal\Object;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use \InvalidArgumentException;

/**
 * The `Timestampable` mixin automates the update of date and user properties for a model.
 *
 * @see TimestampableInterface A full implementation of the mixin.
 */
trait TimestampableTrait
{
    /**
     * The creation timestamp.
     *
     * Set automatically on {@see StorableInterface::create()}.
     *
     * @var DateTime
     */
    private $createdOn;

    /**
     * The user who created the object.
     *
     * @var mixed
     */
    private $createdBy;

    /**
     * The last modification timestamp.
     *
     * Set automatically on {@see StorableInterface::update()}.
     *
     * @var DateTime
     */
    private $updatedOn;

    /**
     * The user who last modified the object.
     *
     * @var mixed
     */
    private $updatedBy;

    /**
     * Retrieve the `Timestampable` mixin's properties.
     *
     * @return array
     */
    public function timestampableProperties()
    {
        return $this->timestampableCreateProperties() + $this->timestampableUpdateProperties();
    }

    /**
     * Retrieve the `Timestampable` mixin's properties.
     *
     * @return array
     */
    public function timestampableCreateProperties()
    {
        return [
            'created_on',
            'created_by'
        ];
    }

    /**
     * Retrieve the `Timestampable` mixin's properties.
     *
     * @return array
     */
    public function timestampableUpdateProperties()
    {
        return [
            'updated_on',
            'updated_by'
        ];
    }

    /**
     * Set when the object was created.
     *
     * @param  DateTime|string $timestamp The timestamp at object's creation.
     * @throws InvalidArgumentException If the creation marker is invalid.
     * @return TimestampableInterface Chainable
     */
    public function setCreatedOn($timestamp)
    {
        if ($timestamp === false) {
            $timestamp = null;
        }

        if ($timestamp !== null) {
            if (is_string($timestamp)) {
                try {
                    $timestamp = new DateTime($timestamp);
                } catch (Exception $e) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid creation marker: %s', $e->getMessage())
                    );
                }
            }

            if (!($timestamp instanceof DateTimeInterface)) {
                throw new InvalidArgumentException(
                    'Invalid creation marker. Must be a date/time string or a DateTime object.'
                );
            }
        }

        $this->createdOn = $timestamp;

        return $this;
    }

    /**
     * Retrieve the creation timestamp.
     *
     * @return DateTime|null
     */
    public function createdOn()
    {
        return $this->createdOn;
    }

    /**
     * Set the creator of the object.
     *
     * @param  mixed $author The creator of the object.
     * @return TimestampableInterface Chainable
     */
    public function setCreatedBy($author)
    {
        $this->createdBy = $author;

        return $this;
    }

    /**
     * Retrieve the creator of the object.
     *
     * @return mixed
     */
    public function createdBy()
    {
        return $this->createdBy;
    }

    /**
     * Set when the object was last modified.
     *
     * @param  DateTime|string $timestamp The timestamp at object's modification.
     * @throws InvalidArgumentException If the update marker is invalid.
     * @return TimestampableInterface Chainable
     */
    public function setUpdatedOn($timestamp)
    {
        if ($timestamp === false) {
            $timestamp = null;
        }

        if ($timestamp !== null) {
            if (is_string($timestamp)) {
                try {
                    $timestamp = new DateTime($timestamp);
                } catch (Exception $e) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid update marker: %s', $e->getMessage())
                    );
                }
            }

            if (!($timestamp instanceof DateTimeInterface)) {
                throw new InvalidArgumentException(
                    'Invalid update marker. Must be a date/time string or a DateTime object.'
                );
            }
        }

        $this->updatedOn = $timestamp;

        return $this;
    }

    /**
     * Retrieve the last modification timestamp.
     *
     * @return DateTime|null
     */
    public function updatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set the updater of the object.
     *
     * @param  mixed $author The updater of the object.
     * @return TimestampableInterface Chainable
     */
    public function setUpdatedBy($author)
    {
        $this->updatedBy = $author;

        return $this;
    }

    /**
     * Retrieve the updater of the object.
     *
     * @return mixed
     */
    public function updatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Mark the object's creation.
     *
     * @todo   Add {@see self::setCreatedBy()} value.
     * @see    StorableTrait::preSave() For the "create" Event.
     * @return boolean
     */
    public function createTimestampable()
    {
        $this->setCreatedOn('now');

        $this->updateTimestampable();

        return true;
    }

    /**
     * Mark the object's last modification.
     *
     * @todo   Add {@see self::setUpdatedBy()} value.
     * @see    StorableTrait::preSave() For the "create" Event.
     * @see    StorableTrait::preUpdate() For the "update" Event.
     * @return boolean
     */
    public function updateTimestampable()
    {
        $this->setUpdatedOn('now');

        return true;
    }
}

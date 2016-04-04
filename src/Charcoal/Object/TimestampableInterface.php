<?php

namespace Charcoal\Object;

/**
 * The `Timestampable` mixin automates the update of date and user properties for a model.
 *
 * The interface adds four read-only properties:
 *
 * - Updated on object creation:
 *   - `created_on` — A timestamp property.
 *   - `created_by` — A user reference property.
 * - Updated on object modification:
 *   - `updated_on` — A timestamp property.
 *   - `updated_by` — A user reference property.
 */
interface TimestampableInterface
{
    /**
     * Set when the object was created.
     *
     * @param  DateTime|string $timestamp The timestamp at object's creation.
     * @return TimestampableInterface Chainable
     */
    public function setCreatedOn($timestamp);

    /**
     * Retrieve the creation timestamp.
     *
     * @return DateTime|null
     */
    public function createdOn();

    /**
     * Set the creator of the object.
     *
     * @param  mixed $author The creator of the object.
     * @return TimestampableInterface Chainable
     */
    public function setCreatedBy($author);

    /**
     * Retrieve the creator of the object.
     *
     * @return mixed
     */
    public function createdBy();

    /**
     * Set when the object was last modified.
     *
     * @param  DateTime|string $timestamp The timestamp at object's modification.
     * @return TimestampableInterface Chainable
     */
    public function setUpdatedOn($timestamp);

    /**
     * Retrieve the last modification timestamp.
     *
     * @return DateTime|null
     */
    public function updatedOn();

    /**
     * Set the updater of the object.
     *
     * @param  mixed $author The updater of the object.
     * @return TimestampableInterface Chainable
     */
    public function setUpdatedBy($author);

    /**
     * Retrieve the updater of the object.
     *
     * @return mixed
     */
    public function updatedBy();
}

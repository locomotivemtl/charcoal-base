<?php

namespace Charcoal\Object;

/**
 * The `Trashable` mixin allows objects to be implicitly deleted.
 *
 * The interface adds two properties:
 *
 * - "deleted_on" — A timestamp property.
 * - "deleted_by" — A user reference property.
 *
 * When soft-deleted (see: "trashed"), the object's "deleted_*" properties are marked with a timestamp
 * and a reference to the _deleter_ instead of explicitly removing the object from the database.
 */
interface TrashableInterface
{
    /**
     * Define whether the object is deleted (with a timestamp) or not (FALSE).
     *
     * @param  DateTime|string|boolean|null $marker A timestamp of deletion or FALSE.
     * @return TrashableInterface Chainable
     */
    public function setDeletedOn($marker);

    /**
     * Retrieve the deletion timestamp, if the object is deleted.
     *
     * @return DateTime|null
     */
    public function deletedOn();

    /**
     * Set the author of the deletion.
     *
     * @param  mixed $author The author of the deleted object.
     * @return TrashableInterface Chainable
     */
    public function setDeletedBy($author);

    /**
     * Retrieve the author of the deleted object.
     *
     * @return mixed
     */
    public function deletedBy();

    /**
     * Determine if the object is trashed.
     *
     * @return boolean
     */
    public function isTrashed();
}

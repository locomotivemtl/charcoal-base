<?php

namespace Charcoal\Object;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use \InvalidArgumentException;

use \Charcoal\Object\TrashableScope;

/**
 * The `Trashable` mixin allows objects to be implicitly deleted.
 *
 * @see TrashableInterface A full implementation of mixin.
 */
trait TrashableTrait
{
    /**
     * The deletion timestamp.
     *
     * Set automatically on {@see StorableInterface::delete()}.
     *
     * @var DateTime
     */
    private $deletedOn;

    /**
     * The user who deleted the object.
     *
     * @var mixed
     */
    private $deletedBy;

    /**
     * Indicates if the object is currently being explicitly deleted.
     *
     * @var boolean
     */
    private $forceDeleting = false;

    /**
     * Scope the trashable model.
     *
     * @return ScopeInterface
     */
    public static function scopeTrashableTrait()
    {
        return new TrashableScope();
    }

    /**
     * Retrieve the `Trashable` mixin's properties.
     *
     * @return array
     */
    public function trashableProperties()
    {
        return [
            'deleted_on',
            'deleted_by'
        ];
    }

    /**
     * Define whether the object is deleted (with a timestamp) or not (FALSE).
     *
     * @param  DateTime|string|boolean|null $marker A timestamp of deletion or FALSE.
     * @throws InvalidArgumentException If the deletion marker is invalid.
     * @return TrashableInterface Chainable
     */
    public function setDeletedOn($marker)
    {
        if ($marker === false) {
            $marker = null;
        }

        if ($marker !== null) {
            if (is_string($marker)) {
                try {
                    $marker = new DateTime($marker);
                } catch (Exception $e) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid deletion marker: %s', $e->getMessage())
                    );
                }
            }

            if (!($marker instanceof DateTimeInterface)) {
                throw new InvalidArgumentException(
                    'Invalid deletion marker. Must be a date/time string or a DateTime object.'
                );
            }
        }

        $this->deletedOn = $marker;

        return $this;
    }

    /**
     * Retrieve the deletion timestamp, if the object is deleted.
     *
     * @return DateTime|null
     */
    public function deletedOn()
    {
        return $this->deletedOn;
    }

    /**
     * Set the author of the deletion.
     *
     * @param  mixed $author The author of the deleted object.
     * @return TrashableInterface Chainable
     */
    public function setDeletedBy($author)
    {
        $this->deletedBy = $author;

        return $this;
    }

    /**
     * Retrieve the author of the deleted object.
     *
     * @return mixed
     */
    public function deletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Determine if the object is trashed.
     *
     * @return boolean
     */
    public function isTrashed()
    {
        return ($this->deletedOn !== null);
    }

    /**
     * Delete the object from storage.
     *
     * This method will trigger either a "soft delete" or a "hard delete"
     * based on the value of {@see self::$forceDeleting}.
     *
     * @return boolean
     */
    public function delete()
    {
        $before = $this->preDelete();

        if ($before === false) {
            return false;
        }

        if ($this->forceDeleting) {
            $result = $this->source()->deleteItem($this);
        } else {
            $properties = $this->trashableProperties();

            $this->saveProperties($properties);

            $result = $this->source()->updateItem($this, $properties);
        }

        if ($result === false) {
            return false;
        }

        $this->postDelete();

        return $result;
    }

    /**
     * Force a hard delete on the object from storage.
     *
     * @return boolean
     */
    public function forceDelete()
    {
        $this->forceDeleting = true;

        $result = $this->delete();

        $this->forceDeleting = false;

        return $result;
    }

    /**
     * Restore hook called before restoring the object.
     *
     * @see    StorableTrait::preDelete()
     * @todo   Add {@see self::setDeletedBy()} value.
     * @return boolean
     */
    protected function preDelete()
    {
        if (!$this->forceDeleting) {
            if ($this instanceof RevisionableInterface) {
                // Content is revisionable
                if ($this->revisionEnabled()) {
                    $this->generateRevision();
                }
            }

            $this->setDeletedOn('now');
        }

        return true;
    }

    /**
     * Restore a soft-deleted object.
     *
     * @return boolean
     */
    public function restore()
    {
        $before = $this->preRestore();

        if ($before === false) {
            return false;
        }

        $properties = $this->trashableProperties();

        $this->saveProperties($properties);

        $result = $this->source()->updateItem($this, $properties);

        if ($result === false) {
            return false;
        }

        $this->postRestore();

        return $result;
    }

    /**
     * Restore hook called before restoring the object.
     *
     * @see    StorableTrait::preUpdate() Based on update hook.
     * @todo   Add {@see self::setDeletedBy()} value.
     * @return boolean
     */
    protected function preRestore()
    {
        $this->setDeletedOn(null);

        return true;
    }

    /**
     * Restore hook called after the object is restored.
     *
     * @see    StorableTrait::postUpdate() Based on update hook.
     * @return boolean
     */
    protected function postRestore()
    {
        return true;
    }
}

<?php

namespace Charcoal\Object;

use \Charcoal\Object\ObjectRevision;

/**
*
*/
trait RevisionableTrait
{
    /**
     * @var bool $revision_enabled
     */
    private $revision_enabled = true;

    /**
     * @param boolean $enabled The (revision) enabled flag.
     * @return RevisionableInterface Chainable
     */
    public function set_revision_enabled($enabled)
    {
        $this->revision_enabled = !!$enabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function revision_enabled()
    {
        return $this->revision_enabled;
    }

    /**
     * This method can be overloaded in concrete implementation to provide a different ObjectRevision class.
     *
     * @return ObjectRevisionInterface
     */
    public function revision_object()
    {
        $rev = new ObjectRevision([
            'logger' => $this->logger
        ]);

        return $rev;
    }

    /**
     * @return ObjectRevision
     * @see ObjectRevision::create_from_object()
     */
    public function generate_revision()
    {
        $rev = $this->revision_object();

        $obj_type = $this->obj_type();
        $rev->create_from_object($obj_type, $this->id());
        $rev->save();

        return $rev;
    }

    /**
     * @return ObjectRevision
     * @see ObejctRevision::last_object_revision
     */
    public function latest_revision()
    {
        $rev = $this->revision_object();

        $obj_type = $this->obj_type();
        $rev->last_object_revision($obj_type, $this->id());

        return $rev;
    }
}

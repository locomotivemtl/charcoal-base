<?php

namespace Charcoal\Object;

use \Charcoal\Object\ObjectRevision;

/**
*
*/
trait RevisionableTrait
{
    /**
     * @var bool $revisionEnabled
     */
    private $revisionEnabled = true;

    /**
     * @param boolean $enabled The (revision) enabled flag.
     * @return RevisionableInterface Chainable
     */
    public function setRevisionEnabled($enabled)
    {
        $this->revisionEnabled = !!$enabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function revisionEnabled()
    {
        return $this->revisionEnabled;
    }

    /**
     * This method can be overloaded in concrete implementation to provide a different (custom) ObjectRevision class.
     *
     * @return ObjectRevisionInterface
     */
    public function revisionObject()
    {
        $rev = new ObjectRevision([
            'logger' => $this->logger
        ]);

        return $rev;
    }

    /**
     * @return ObjectRevision
     * @see ObjectRevision::create_fromObject()
     */
    public function generateRevision()
    {
        $rev = $this->revisionObject();

        $rev->create_fromObject($this);
        if (!empty($rev->dataDiff())) {
            $rev->save();
        }

        return $rev;
    }

    /**
     * @return ObjectRevision
     * @see ObejctRevision::lastObjectRevision
     */
    public function latestRevision()
    {
        $rev = $this->revisionObject();
        $rev = $rev->lastObjectRevision($this);

        return $rev;
    }
}

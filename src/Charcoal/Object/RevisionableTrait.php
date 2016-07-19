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
     * @return \Charcoal\Object\ObjectRevisionInterface
     */
    public function revisionObject()
    {
        $rev = $this->modelFactory()->create(ObjectRevision::class);
        return $rev;
    }

    /**
     * @return \Charcoal\Object\ObjectRevision
     * @see \Charcoal\Object\ObjectRevision::create_fromObject()
     */
    public function generateRevision()
    {
        $rev = $this->revisionObject();

        $rev->createFromObject($this);
        if (!empty($rev->dataDiff())) {
            $rev->save();
        }

        return $rev;
    }

    /**
     * @return \Charcoal\Object\ObjectRevision
     * @see \Charcoal\Object\ObejctRevision::lastObjectRevision
     */
    public function latestRevision()
    {
        $rev = $this->revisionObject();
        $rev = $rev->lastObjectRevision($this);

        return $rev;
    }

    /**
     * A model factory must be provided on implementing classes.
     *
     * @return FactoryInterface
     */
    abstract protected function modelFactory();
}

<?php

namespace Charcoal\Object;

/**
 *
 */
interface RevisionableInterface
{
    /**
     * @param boolean $enabled The (revision) enabled flag.
     * @return RevisionableInterface Chainable
     */
    public function setRevisionEnabled($enabled);

    /**
     * @return boolean
     */
    public function revisionEnabled();

    /**
     * This method can be overloaded in concrete implementation to provide a different ObjectRevision class.
     *
     * @return \Charcoal\Object\ObjectRevisionInterface
     */
    public function revisionObject();

    /**
     * @return \Charcoal\Object\ObjectRevisionInterface
     */
    public function generateRevision();

    /**
     * @return \Charcoal\Object\ObjectRevisionInterface
     */
    public function latestRevision();
}

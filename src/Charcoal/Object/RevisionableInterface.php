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
    public function set_revision_enabled($enabled);

    /**
     * @return boolean
     */
    public function revision_enabled();

    /**
     * This method can be overloaded in concrete implementation to provide a different ObjectRevision class.
     *
     * @return \Charcoal\Object\ObjectRevisionInterface
     */
    public function revision_object();

    /**
     * @return \Charcoal\Object\ObjectRevisionInterface
     */
    public function generate_revision();

    /**
     * @return \Charcoal\Object\ObjectRevisionInterface
     */
    public function latest_revision();
}

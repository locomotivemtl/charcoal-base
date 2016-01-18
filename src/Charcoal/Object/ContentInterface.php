<?php

namespace Charcoal\Object;

interface ContentInterface
{
    /**
     * @param boolean $active The active flag.
     * @return Content Chainable
     */
    public function setActive($active);

    /**
     * @return boolean
     */
    public function active();

    /**
     * @param integer $position The position index.
     * @return Content Chainable
     */
    public function setPosition($position);

    /**
     * @return integer
     */
    public function position();

    /**
     * @param DateTime|string $created The created date.
     * @return Content Chainable
     */
    public function setCreated($created);

    /**
     * @return DateTime|null
     */
    public function created();

    /**
     * @param mixed $createdBy The author, at object creation.
     * @return Content Chainable
     */
    public function setCreatedBy($createdBy);

    /**
     * @return mixed
     */
    public function createdBy();

    /**
     * @param DateTime|string $lastModified The last modified date.
     * @return Content Chainable
     */
    public function setLastModified($lastModified);

    /**
     * @return DateTime
     */
    public function lastModified();

    /**
     * @param mixed $lastModifiedBy The author, at object modificaition (update).
     * @return Content Chainable
     */
    public function setLastModifiedBy($lastModifiedBy);

    /**
     * @return mixed
     */
    public function lastModifiedBy();
}

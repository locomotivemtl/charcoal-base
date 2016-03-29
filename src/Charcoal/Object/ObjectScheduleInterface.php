<?php

namespace Charcoal\Object;

/**
 *
 */
interface ObjectScheduleInterface
{
    /**
     * @param string $objType The object type (type-ident).
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setObjType($objType);

    /**
     * @return string
     */
    public function objType();

    /**
     * @param mixed $objId The object ID.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setObjId($objId);

    /**
     * @return mixed
     */
    public function objId();

    /**
     * Set the date/time the item should be processed at.
     *
     * @param  null|string|DateTimeInterface $ts A date/time string or object.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return QueueItemInterface Chainable
     */
    public function setProcessingDate($ts);

    /**
     * Retrieve the date/time the item should be processed at.
     *
     * @return null|DateTimeInterface
     */
    public function processingDate();

    /**
     * Set the date/time the item was processed at.
     *
     * @param  null|string|DateTimeInterface $ts A date/time string or object.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return QueueItemInterface Chainable
     */
    public function setProcessedDate($ts);

    /**
     * Retrieve the date/time the item was processed at.
     *
     * @return null|DateTimeInterface
     */
    public function processedDate();
}

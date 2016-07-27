<?php

namespace Charcoal\Object;

/**
 *
 */
interface ObjectScheduleInterface
{
    /**
     * @param string $targetType The object type (type-ident).
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setTargetType($targetType);

    /**
     * @return string
     */
    public function targetType();

    /**
     * @param mixed $targetId The object ID.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setTargetId($targetId);

    /**
     * @return mixed
     */
    public function targetId();

    /**
     * Set the date/time the item should be processed at.
     *
     * @param  null|string|DateTimeInterface $ts A date/time string or object.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return QueueItemInterface Chainable
     */
    public function setScheduledDate($ts);

    /**
     * Retrieve the date/time the item should be processed at.
     *
     * @return null|DateTimeInterface
     */
    public function scheduledDate();

    /**
     * @param array|string $data The data diff.
     * @return ObjectRevision
     */
    public function setDataDiff($data);

    /**
     * @return array
     */
    public function dataDiff();

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

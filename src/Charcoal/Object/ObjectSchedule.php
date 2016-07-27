<?php

namespace Charcoal\Object;

// Dependencies from `PHP`
use \DateTime;
use \DateTimeInterface;
use \Exception;
use \RuntimeException;
use \InvalidArgumentException;

// Dependency from 'charcoal-factory'
use \Charcoal\Factory\FactoryInterface;

// Dependency from 'charcoal-core'
use \Charcoal\Model\AbstractModel;

// Local namespace dependencies
use \Charcoal\Object\ObjectScheduleInterface;

/**
 * The object schedule class allows object properties to be changed at a scheduled time.
 *
 * ## Required Services
 *
 * - "model/factory" — {@see \Charcoal\Model\ModelFactory}
 */
class ObjectSchedule extends AbstractModel implements ObjectScheduleInterface
{
    /**
     * Store the factory instance for the current class.
     *
     * @var FactoryInterface
     */
    private $modelFactory;

    /**
     * The object type of the scheduled object (required).
     *
     * @var string
     */
    private $targetType;

    /**
     * The object ID of the scheduled object (required).
     *
     * @var mixed
     */
    private $targetId;

    /**
     * When the item should be processed.
     *
     * The date/time at which this queue item job should be ran.
     * If NULL, 0, or a past date/time, then it should be performed immediately.
     *
     * @var DateTimeInterface $scheduledDate
     */
    private $scheduledDate;

    /**
     * The property identifier of the scheduled object (required).
     *
     * @var string
     */
    private $dataDiff = [];

    /**
     * Whether the item has been processed.
     *
     * @var boolean $processed
     */
    private $processed = false;

    /**
     * When the item was processed.
     *
     * @var DateTimeInterface $processedDate
     */
    private $processedDate;

    /**
     * Set an object model factory.
     *
     * @param FactoryInterface $factory The model factory, to create objects.
     * @return ObjectContainerInterface Chainable
     */
    public function setModelFactory(FactoryInterface $factory)
    {
        $this->modelFactory = $factory;

        return $this;
    }

    /**
     * Retrieve the object model factory.
     *
     * @throws RuntimeException If the model factory was not previously set.
     * @return FactoryInterface
     */
    protected function modelFactory()
    {
        if (!isset($this->modelFactory)) {
            throw new RuntimeException(
                sprintf('Model Factory is not defined for "%s"', get_class($this))
            );
        }

        return $this->modelFactory;
    }

    /**
     * Set the scheduled object's type.
     *
     * @param string $targetType The object type (model).
     * @throws InvalidArgumentException If the object type parameter is not a string.
     * @return ObjectScheduleInterface Chainable
     */
    public function setTargetType($targetType)
    {
        if (!is_string($targetType)) {
            throw new InvalidArgumentException(
                'Scheduled object type must be a string.'
            );
        }

        $this->targetType = $targetType;

        return $this;
    }

    /**
     * Retrieve the scheduled object's type.
     *
     * @return string
     */
    public function targetType()
    {
        return $this->targetType;
    }

    /**
     * Set the scheduled object's ID.
     *
     * @param mixed $targetId The object ID.
     * @return ObjectScheduleInterface Chainable
     */
    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;

        return $this;
    }

    /**
     * Retrieve the scheduled object's ID.
     *
     * @return mixed
     */
    public function targetId()
    {
        return $this->targetId;
    }

    /**
     * @param array|string $data The data diff.
     * @return ObjectRevision
     */
    public function setDataDiff($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        if ($data === null) {
            $data = [];
        }
        $this->dataDiff = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function dataDiff()
    {
        return $this->dataDiff;
    }

    /**
     * Set the schedule's processed status.
     *
     * @param boolean $processed Whether the schedule has been processed.
     * @return ObjectScheduleInterface Chainable
     */
    public function setProcessed($processed)
    {
        $this->processed = !!$processed;

        return $this;
    }

    /**
     * Determine if the schedule has been processed.
     *
     * @return boolean
     */
    public function processed()
    {
        return $this->processed;
    }

    /**
     * Set the date/time the item should be processed at.
     *
     * @param  null|string|DateTimeInterface $ts A date/time string or object.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return QueueItemInterface Chainable
     */
    public function setScheduledDate($ts)
    {
        if ($ts === null) {
            $this->scheduledDate = null;
            return $this;
        }

        if (is_string($ts)) {
            try {
                $ts = new DateTime($ts);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    sprintf('%s (%s)', $e->getMessage(), $ts)
                );
            }
        }

        if (!($ts instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Processing Date" value. Must be a date/time string or a DateTime object.'
            );
        }

        $this->scheduledDate = $ts;

        return $this;
    }

    /**
     * Retrieve the date/time the item should be processed at.
     *
     * @return null|DateTimeInterface
     */
    public function scheduledDate()
    {
        return $this->scheduledDate;
    }

    /**
     * Set the date/time the item was processed at.
     *
     * @param  null|string|DateTimeInterface $ts A date/time string or object.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return QueueItemInterface Chainable
     */
    public function setProcessedDate($ts)
    {
        if ($ts === null) {
            $this->processedDate = null;
            return $this;
        }

        if (is_string($ts)) {
            try {
                $ts = new DateTime($ts);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    sprintf('%s (%s)', $e->getMessage(), $ts)
                );
            }
        }

        if (!($ts instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Processed Date" value. Must be a date/time string or a DateTime object.'
            );
        }

        $this->processedDate = $ts;

        return $this;
    }

    /**
     * Retrieve the date/time the item was processed at.
     *
     * @return null|DateTimeInterface
     */
    public function processedDate()
    {
        return $this->processedDate;
    }

    /**
     * Hook called before saving the item.
     *
     * Presets the item as _to-be_ processed and queued now.
     *
     * @return QueueItemInterface Chainable
     */
    public function preSave()
    {
        parent::preSave();

        $this->setProcessed(false);

        return true;
    }

    /**
     * Process the item.
     *
     * @param  callable $callback        An optional callback routine executed after the item is processed.
     * @param  callable $successCallback An optional callback routine executed when the item is resolved.
     * @param  callable $failureCallback An optional callback routine executed when the item is rejected.
     * @return boolean  Success / Failure
     */
    public function process(
        callable $callback = null,
        callable $successCallback = null,
        callable $failureCallback = null
    ) {

        if ($this->processed() === true) {
            // Do not process twice, ever.
            return null;
        }

        if ($this->targetType() === null) {
            $this->logger->error('Can not process object schedule: no object type defined.');
            return false;
        }

        if ($this->targetId() === null) {
            $this->logger->error(
                sprintf('Can not process object schedule: no object "%s" ID defined.', $this->targetType())
            );
            return false;
        }

        if (empty($this->dataDiff())) {
            $this->logger->error('Can not process object schedule: no changes (diff) defined.');
            return false;
        }

        $obj = $this->modelFactory()->create($this->targetType());
        $obj->load($this->targetId());
        if (!$obj->id()) {
            $this->logger->error(sprintf('Can not load "%s" object %id', $this->targetType(), $this->targetId()));
        }
        $obj->setData($this->dataDiff());
        $update = $obj->update(array_keys($this->dataDiff()));

        if ($update) {
            $this->setProcessed(true);
            $this->setProcessedDate('now');
            $this->update(['processed', 'processed_date']);

            if ($successCallback !== null) {
                $successCallback($this);
            }
        } else {
            if ($failureCallback !== null) {
                $failureCallback($this);
            }
        }

        if ($callback !== null) {
            $callback($this);
        }

        return $update;
    }
}

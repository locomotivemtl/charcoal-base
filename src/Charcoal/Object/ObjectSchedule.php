<?php

namespace Charcoal\Object;

// Dependencies from `PHP`
use \DateTime;
use \DateTimeInterface;
use \Exception;
use \InvalidArgumentException;

// Module `charcoal-factory` dependencies
use \Charcoal\Factory\FactoryInterface;

// Module `charcoal-core` dependencies
use \Charcoal\Model\AbstractModel;

// Local namespace dependencies
use \Charcoal\Object\ObjectScheduleInterface;

/**
 * Object schedule allows object properties to be changed at a scheduled time.
 */
class ObjectSchedule extends AbstractModel implements ObjectScheduleInterface
{
    /**
     * @var FactoryInterface $modelFactory
     */
    private $modelFactory;

    /**
     * Object type of this revision (required)
     * @var string $objType
     */
    private $objType;

    /**
     * Object ID of this revision (required)
     * @var mixed $objectId
     */
    private $objId;

    /**
     * @var string $propertyIdent
     */
    private $propertyIdent;

    /**
     * @var mixed $newValue
     */
    private $newValue;

    /**
     * Whether the item has been processed.
     *
     * @var boolean $processed
     */
    private $processed = false;

    /**
     * When the item should be processed.
     *
     * The date/time at which this queue item job should be ran.
     * If NULL, 0, or a past date/time, then it should be performed immediately.
     *
     * @var DateTimeInterface $processingDate
     */
    private $processingDate;

    /**
     * When the item was processed.
     *
     * @var DateTimeInterface $processedDate
     */
    private $processedDate;

    /**
     * @param FactoryInterface $factory The model factory, to create objects.
     * @return ObjectContainerInterface Chainable
     */
    public function setModelFactory(FactoryInterface $factory)
    {
        $this->modelFactory = $factory;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    protected function modelFactory()
    {
        return $this->modelFactory;
    }

    /**
     * @param string $objType The object type (type-ident).
     * @throws InvalidArgumentException If the obj type parameter is not a string.
     * @return ObjectScheduleInterface Chainable
     */
    public function setObjType($objType)
    {
        if (!is_string($objType)) {
            throw new InvalidArgumentException(
                'Revisions obj type must be a string.'
            );
        }
        $this->objType = $objType;
        return $this;
    }

    /**
     * @return string
     */
    public function objType()
    {
        return $this->objType;
    }

    /**
     * @param mixed $objId The object ID.
     * @return ObjectScheduleInterface Chainable
     */
    public function setObjId($objId)
    {
        $this->objId = $objId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function objId()
    {
        return $this->objId;
    }

    /**
     * @param string $propertyIdent The property identifier.
     * @return ObjectScheduleInterface Chainable
     */
    public function setPropertyIdent($propertyIdent)
    {
        $this->propertyIdent = $propertyIdent;
        return $this;
    }

    /**
     * @return string
     */
    public function propertyIdent()
    {
        return $this->propertyIdent;
    }

    /**
     * @param mixed $val The new value to set on object's property.
     * @return ObjectScheduleInterface Chainable
     */
    public function setNewValue($val)
    {
        $this->newValue = $val;
        return $this;
    }

    /**
     * @return mixed
     */
    public function newValue()
    {
        return $this->newValue;
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
    public function setProcessingDate($ts)
    {
        if ($ts === null) {
            $this->processingDate = null;
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

        $this->processingDate = $ts;

        return $this;
    }

    /**
     * Retrieve the date/time the item should be processed at.
     *
     * @return null|DateTimeInterface
     */
    public function processingDate()
    {
        return $this->processingDate;
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

        if ($this->objType() === null) {
            $this->logger->error('Can not process object schedule: no object type defined.');
            return false;
        }
        if ($this->objId() === null) {
            $this->logger->error(
                sprintf('Can not process object schedule: no object "%s" ID defined.', $this->objType())
            );
            return false;
        }
        if ($this->propertyIdent() === null) {
            $this->logger->error('Can not process object schedule: no property identifer defined.');
            return false;
        }

        $obj = $this->modelFactory()->create($this->objType());
        $obj->load($this->objId());
        if (!$obj->id()) {
            $this->logger->error(sprintf('Can not load "%s" object %id', $this->objType(), $this->objId()));
        }
        $obj[$this->propertyIdent()] = $this->newValue();
        $update = $obj->update([$this->propertyIdent()]);

        if ($update) {
            $this->setProcessed(true);
            $this->setProcessedDate('now');
            $this->update();

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

<?php

namespace Charcoal\Object;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use \InvalidArgumentException;

/**
 * The `Expirable` mixin defines expiration properties for a model.
 *
 * @see ExpirableInterface A full implementation of mixin.
 * @see PublishableTrait Pairs well with the `Publishable` mixin.
 */
trait ExpirableTrait
{
    /**
     * The expiration timestamp.
     *
     * @var DateTime
     */
    private $expiredOn;

    /**
     * The user who expired the object.
     *
     * @var mixed
     */
    private $expiredBy;

    /**
     * Retrieve the `Expirable` mixin's properties.
     *
     * @return array
     */
    public function expirableProperties()
    {
        return [
            'expired_on',
            'expired_by'
        ];
    }

    /**
     * Define whether the object is expired or scheduled to be expired
     * (with a timestamp) or not (FALSE).
     *
     * @param  DateTime|string|boolean|null $marker A timestamp for expiration or FALSE.
     * @throws InvalidArgumentException If the expiration marker is invalid.
     * @return TrashableInterface Chainable
     */
    public function setExpiredOn($marker)
    {
        if ($marker === false) {
            $marker = null;
        }

        if ($marker !== null) {
            if (is_string($marker)) {
                try {
                    $marker = new DateTime($marker);
                } catch (Exception $e) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid expiration marker: %s', $e->getMessage())
                    );
                }
            }

            if (!($marker instanceof DateTimeInterface)) {
                throw new InvalidArgumentException(
                    'Invalid expiration marker. Must be a date/time string or a DateTime object.'
                );
            }
        }

        $this->expiredOn = $marker;

        return $this;
    }

    /**
     * Retrieve the expiration timestamp, if the object is expirable.
     *
     * @return DateTime|null
     */
    public function expiredOn()
    {
        return $this->expiredOn;
    }

    /**
     * Set the author of the expiration.
     *
     * @param  mixed $author The author of the expirable object.
     * @return ExpirableInterface Chainable
     */
    public function setExpiredBy($author)
    {
        $this->expiredBy = $author;

        return $this;
    }

    /**
     * Retrieve the author of the expirable object.
     *
     * @return mixed
     */
    public function expiredBy()
    {
        return $this->expiredBy;
    }

    /**
     * Determine if the object is expired.
     *
     * @return boolean
     */
    public function isExpired()
    {
        if ($this->expiredOn instanceof DateTimeInterface) {
            $now = new DateTime();

            return ($now >= $this->expiredOn);
        }

        return false;
    }

    /**
     * Expire the object.
     *
     * Examples of implementation:
     *
     * - With {@see PublishableInterface}, change the `publication_status` property to "draft".
     * - With {@see TrashableInterface}, soft-delete the object;
     * - With {@see Content}, change the `active` property to FALSE;
     * - With {@see \Charcoal\Email}, send a notification to the author.
     *
     * @todo   Should this method be abstract?
     * @todo   Implement `preExpire()` and `postExpire()` events.
     * @return boolean
     */
    public function expire()
    {
        if (!$this->expiredOn) {
            $this->setExpiredOn('now');
        }

        $properties = $this->expirableProperties();

        if ($this instanceof TrashableInterface) {
            $this->preDelete();
            $properties += $this->trashableProperties();
        } elseif ($this->hasProperty('active')) {
            $this->setActive(false);
            $properties[] = 'active';
        }

        $this->saveProperties($properties);
        $result = $this->source()->updateItem($this, $properties);

        return $result;
    }
}

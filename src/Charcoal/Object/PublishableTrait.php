<?php

namespace Charcoal\Object;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use \InvalidArgumentException;

/**
 * The `Publishable` mixin defines publication properties for a model.
 *
 * Recommended methods to implement if `Publishable` and `Expirable` are used:
 *
 * - `hasPublicationEnded()`
 * - `hasPublicationStarted()`
 *
 * @see PublishableInterface A full implementation of mixin.
 * @see ExpirableTrait Pairs well with the `Expirable` mixin.
 */
trait PublishableTrait
{
    /**
     * The publication timestamp.
     *
     * @var DateTime
     */
    private $publishedOn;

    /**
     * The user who published the object.
     *
     * @var mixed
     */
    private $publishedBy;

    /**
     * The publication staus of the object.
     *
     * @var string
     */
    private $publicationStatus;

    /**
     * Retrieve the `Publishable` mixin's properties.
     *
     * @return array
     */
    public function publishableProperties()
    {
        return [
            'published_on',
            'published_by',
            'publication_status'
        ];
    }

    /**
     * Define whether the object is published or scheduled to be published
     * (with a timestamp) or not (FALSE).
     *
     * @param  DateTime|string|boolean|null $marker A timestamp for publication or FALSE to remain unpublished.
     * @throws InvalidArgumentException If the publishing marker is invalid.
     * @return TrashableInterface Chainable
     */
    public function setPublishedOn($marker)
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
                        sprintf('Invalid publishing marker: %s', $e->getMessage())
                    );
                }
            }

            if (!($marker instanceof DateTimeInterface)) {
                throw new InvalidArgumentException(
                    'Invalid publishing marker. Must be a date/time string or a DateTime object.'
                );
            }
        }

        $this->publishedOn = $marker;

        return $this;
    }

    /**
     * Retrieve the publication timestamp, if the object is published.
     *
     * @return DateTime|null
     */
    public function publishedOn()
    {
        return $this->publishedOn;
    }

    /**
     * Set the author of the publication.
     *
     * @param  mixed $author The author of the publishable object.
     * @return PublishableInterface Chainable
     */
    public function setPublishedBy($author)
    {
        $this->publishedBy = $author;

        return $this;
    }

    /**
     * Retrieve the author of the publication.
     *
     * @return mixed
     */
    public function publishedBy()
    {
        return $this->publishedBy;
    }

    /**
     * Retrieve the available publication statuses.
     *
     * @see    PublishableInterface For descriptions of statuses.
     * @todo   Should we retrieve available statuses from property metadata?
     * @return array
     */
    public function availableStatuses()
    {
        return [
            'draft',
            'pending',
            'published'
        ];
    }

    /**
     * Set the publication status of the object.
     *
     * @param  string $status The publication status.
     * @throws InvalidArgumentException If the status is invalid.
     * @return PublishableInterface Chainable
     */
    public function setPublicationStatus($status)
    {
        $statuses = $this->availableStatuses();

        if ($status === false) {
            $status = null;
        }

        if ($status !== null) {
            if (!in_array($status, $statuses)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Invalid publication status: "%s". Must be one of: %s',
                        $status,
                        implode(', ', $statuses)
                    )
                );
            }
        }

        $this->publicationStatus = $status;

        return $this;
    }

    /**
     * Retrieve the publication status of the object.
     *
     * @see    PublishableInterface For descriptions of statuses.
     * @return string|null
     */
    public function publicationStatus()
    {
        $status = $this->publicationStatus;

        if (!$status || $status === 'published') {
            $status = $this->publicationStatusFromDate();
        }

        return $status;
    }

    /**
     * Retrieve the publication status resolved by the timeframe.
     *
     * - If no "published_on" date/time is set, the publication has not started (draft or expired).
     * - If no "expired_on" date/time is set, the publication has not ended (expired, published, or scheduled).
     *
     * @return string
     */
    private function publicationStatusFromDate()
    {
        $now   = new DateTime();
        $start = $this->publishedOn();
        $until = ($this instanceof ExpirableInterface)
               ? $this->expiredOn()
               : null;

        if (!$start) {
            if (!$until || $now < $until) {
                return 'draft';
            } else {
                return 'expired';
            }
        } else {
            if ($now < $start) {
                return 'scheduled';
            } else {
                if (!$until || $now < $until) {
                    return 'published';
                } else {
                    return 'expired';
                }
            }
        }
    }

    /**
     * Determine if the object has been published.
     *
     * The timeframe is taken into account.
     *
     * @return boolean
     */
    public function isPublished()
    {
        return ($this->publicationStatus() === 'published');
    }

    /**
     * Publish the object.
     *
     * This method will change the `publication_status` property to "published"
     * and set the date/time to _now_ for the "published_on" property.
     *
     * @todo   Implement `prePublish()` and `postPublish()` events.
     * @return boolean
     */
    public function publish()
    {
        if (!$this->isPublished()) {
            $this->setPublishedOn('now');
            $this->setPublicationStatus('published');

            $properties = $this->publishableProperties();
            $this->saveProperties($properties);
            $result = $this->source()->updateItem($this, $properties);

            return $result;
        }

        return true;
    }

    /**
     * Unpublish the object.
     *
     * This method will change the `publication_status` property to "draft"
     * and unset the "published_on" property.
     *
     * @todo   Implement `preUnpublish()` and `postUnpublish()` events.
     * @return boolean
     */
    public function unpublish()
    {
        if ($this->isPublished()) {
            $this->setPublishedOn(null);
            $this->setPublicationStatus('draft');

            $properties = $this->publishableProperties();
            $this->saveProperties($properties);
            $result = $this->source()->updateItem($this, $properties);

            return $result;
        }

        return true;
    }

    /**
     * Save hook called before creating and updating the object.
     *
     * @todo   Add {@see self::setPublishedBy()} value.
     * @todo   There should be 3 events: create, update, save (called on either "create" or "update").
     * @see    StorableTrait::preSave()
     * @see    StorableTrait::preUpdate()
     * @return boolean
     */
    public function savePublishableTrait()
    {
        if (!$this->publicationStatus) {
            $this->setPublicationStatus((self::PUBLISHED_BY_DEFAULT) ? 'published' : 'draft');
        }

        if (!$this->publishedOn && $this->publicationStatus === 'published') {
            $this->setPublishedOn('now');
        }

        return true;
    }
}

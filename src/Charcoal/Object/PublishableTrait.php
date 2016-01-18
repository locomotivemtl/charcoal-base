<?php

namespace Charcoal\Object;

// Dependencies from `PHP`
use \DateTime as DateTime;
use \DateTimeInterface as DateTimeInterface;
use \Exception as Exception;
use \InvalidArgumentException as InvalidArgumentException;

/**
* A full implementation, as trait, of the `PublishableInterface`.
*/
trait PublishableTrait
{
    /**
     * @var DateTime $publishDate
     */
    private $publishDate;
    /**
     * @var DateTime $expiryDate
     */
    private $expiryDate;

    /**
     * @var string $publishStatus
     */
    private $publishStatus;

    /**
     * @param string|DateTime|null $publishDate The publishing date.
     * @throws InvalidArgumentException If the datetime is invalid.
     * @return PublishableInterface Chainable
     */
    public function setPublishDate($publishDate)
    {
        if ($publishDate === null) {
            $this->publishDate = null;
            return $this;
        }
        if (is_string($publishDate)) {
            try {
                $publishDate = new DateTime($publishDate);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    'Invalid publish date: '.$e->getMessage()
                );
            }
        }
        if (!($publishDate instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Publish Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->publishDate = $publishDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function publishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param string|DateTime|null $expiryDate The expiry date.
     * @throws InvalidArgumentException If the datetime is invalid.
     * @return PublishableInterface Chainable
     */
    public function setExpiryDate($expiryDate)
    {
        if ($expiryDate === null) {
            $this->expiryDate = null;
            return $this;
        }
        if (is_string($expiryDate)) {
            try {
                $expiryDate = new DateTime($expiryDate);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    'Invalid expiry date: '.$e->getMessage()
                );
            }
        }
        if (!($expiryDate instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Expiry Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->expiryDate = $expiryDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function expiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param string $status The publish status (draft, pending or published).
     * @throws InvalidArgumentException If the status is not one of the 3 valid status.
     * @return PublishableTrait Chainable
     */
    public function setPublishStatus($status)
    {
        $validStatus = [
            'draft',
            'pending',
            'published'
        ];
        if (!in_array($status, $validStatus)) {
            throw new InvalidArgumentException(
                sprintf('Status "%s" is not a valid publish status.', $status)
            );
        }
        $this->publishStatus = $status;
        return $this;
    }

    /**
     * Get the object's publish status.
     *
     * Status can be:
     * - `draft`
     * - `pending`
     * - `published`
     * - `upcoming`
     * - `expired`
     *
     * Note that the `upcoming` and `expired` status are specialized status when
     * the object is set to `published` but the `publishDate` or `expiryDate` do not match.
     *
     * @return string
     */
    public function publishStatus()
    {
        $status = $this->publishStatus;
        if (!$status || $status == 'published') {
            $status = $this->publishDateStatus();
        }
        return $status;
    }

    /**
     * Get the "publish status" from the publish date / expiry date.
     *
     * - If no publish date is set, then it is assumed to be "always published." (or expired)
     * - If no expiry date is set, then it is assumed to never expire.
     *
     * @return string
     */
    private function publishDateStatus()
    {
        $now = new DateTime();
        $publish = $this->publishDate();
        $expiry = $this->expiryDate();

        if (!$publish) {
            if (!$expiry || $now < $expiry) {
                return 'published';
            } else {
                return 'expired';
            }
        } else {
            if ($now < $publish) {
                return 'upcoming';
            } else {
                if (!$expiry || $now < $expiry) {
                    return 'published';
                } else {
                    return 'expired';
                }
            }
        }
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return ($this->publishStatus() == 'published');
    }
}

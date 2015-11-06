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
    * @var DateTime $publish_date
    */
    private $publish_date;
    /**
    * @var DateTime $expiry_date
    */
    private $expiry_date;

    /**
    * @var string $publish_status
    */
    private $publish_status;

    /**
    * @param string|DateTime $publish_date
    * @throws InvalidArgumentException
    */
    public function set_publish_date($publish_date)
    {
        if ($publish_date === null) {
            $this->publish_date = null;
            return $this;
        }
        if (is_string($publish_date)) {
            try {
                $publish_date = new DateTime($publish_date);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    'Invalid publish date: '.$e->getMessage()
                );
            }
        }
        if (!($publish_date instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Publish Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->publish_date = $publish_date;
        return $this;
    }

    /**
    * @return DateTime|null
    */
    public function publish_date()
    {
        return $this->publish_date;
    }

    /**
    * @param string|DateTime $expiry_date
    * @throws InvalidArgumentException
    */
    public function set_expiry_date($expiry_date)
    {
        if ($expiry_date === null) {
            $this->expiry_date = null;
            return $this;
        }
        if (is_string($expiry_date)) {
            try {
                $expiry_date = new DateTime($expiry_date);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    'Invalid expiry date: '.$e->getMessage()
                );
            }
        }
        if (!($expiry_date instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Expiry Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->expiry_date = $expiry_date;
        return $this;
    }

    /**
    * @return DateTime|null
    */
    public function expiry_date()
    {
        return $this->expiry_date;
    }

    /**
    * @param string $status
    * @throws InvalidArgumentException
    * @return PublishableTrait Chainable
    */
    public function set_publish_status($status)
    {
        $valid_status = [
            'draft',
            'pending',
            'published'
        ];
        if (!in_array($status, $valid_status)) {
            throw new InvalidArgumentException(
                sprintf('Status "%s" is not a valid publish status.', $status)
            );
        }
        $this->publish_status = $status;
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
    * the object is set to `published` but the `publish_date` or `expiry_date` do not match.
    *
    * @return string
    */
    public function publish_status()
    {
        $status = $this->publish_status;
        if (!$status || $status == 'published') {
            $status = $this->publish_date_status();
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
    private function publish_date_status()
    {
        $now = new DateTime();
        $publish = $this->publish_date();
        $expiry = $this->expiry_date();

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
    public function is_published()
    {
        return ($this->publish_status() == 'published');
    }
}

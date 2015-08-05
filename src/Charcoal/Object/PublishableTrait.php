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
    //const PUBLISH_STATUS_UPCOMING = 'upcoming';
    //const PUBLISH_STATUS_PUBLISHED = 'published';
    //const PUBLISH_STATUS_EXPIRED = 'expired';

    /**
    * @var DateTime $_publish_date
    */
    protected $_publish_date;
    /**
    * @var DateTime $_expiry_date
    */
    protected $_expiry_date;

    /**
    * @param array $data
    * @return PublishableTrait Chainable
    */
    public function set_publishable_data(array $data)
    {
        if (isset($data['publish_date']) && $data['publish_date'] !== null) {
            $this->set_publish_date($data['publish_date']);
        }
        if (isset($data['expiry_date']) && $data['expiry_date'] !== null) {
            $this->set_expiry_date($data['expiry_date']);
        }
        return $this;
    }

    /**
    * @param string|DateTime $publish_date
    * @throws InvalidArgumentException
    */
    public function set_publish_date($publish_date)
    {
        if (is_string($publish_date)) {
            try {
                $publish_date = new DateTime($publish_date);
            } catch (Exception $e) {
                throw new InvalidArgumentException($e->getMessage());
            }
        }
        if (!($publish_date instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Publish Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->_publish_date = $publish_date;
        return $this;
    }

    /**
    * @return DateTime|null
    */
    public function publish_date()
    {
        return $this->_publish_date;
    }

    /**
    * @param string|DateTime $expiry_date
    * @throws InvalidArgumentException
    */
    public function set_expiry_date($expiry_date)
    {
        if (is_string($expiry_date)) {
            try {
                $expiry_date = new DateTime($expiry_date);
            } catch (Exception $e) {
                throw new InvalidArgumentException($e->getMessage());
            }
        }
        if (!($expiry_date instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Expiry Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->_expiry_date = $expiry_date;
        return $this;
    }

    /**
    * @return DateTime|null
    */
    public function expiry_date()
    {
        return $this->_expiry_date;
    }


    /**
    * Get the "publish status" from the publish date / expiry date.
    *
    * - If no publish date is set, then it is assumed to be "always published." (or expired)
    * - If no expiry date is set, then it is assumed to never expire.
    *
    * @return string
    */
    public function publish_status()
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
}

<?php

namespace Charcoal\Object;

/**
*
*/
interface PublishableInterface
{
    /**
    * @param array $data
    * @return PublishableTrait Chainable
    */
    public function set_publishable_data();

    /**
    * @param string|DateTime $publish_date
    * @throws InvalidArgumentException
    */
    public function set_publish_date($publish_date);

    /**
    * @return DateTime|null
    */
    public function publish_date();

    /**
    * @param string|DateTime $expiry_date
    * @throws InvalidArgumentException
    */
    public function set_expiry_date($expiry_date);

    /**
    * @return DateTime|null
    */
    public function expiry_date();

    /**
    * Get the "publish status" from the publish date / expiry date.
    *
    * - If no publish date is set, then it is assumed to be "always published." (or expired)
    * - If no expiry date is set, then it is assumed to never expire.
    *
    * @return string
    */
    public function publish_status();
}

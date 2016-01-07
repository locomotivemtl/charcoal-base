<?php

namespace Charcoal\Object;

/**
 *
 */
interface PublishableInterface
{
    /**
     * @param string|DateTime $publish_date The publish date.
     * @return PublishableTrait Chainable
     */
    public function set_publish_date($publish_date);

    /**
     * @return DateTime|null
     */
    public function publish_date();

    /**
     * @param string|DateTime $expiry_date The expiry date.
     * @return PublishableTrait Chainable
     */
    public function set_expiry_date($expiry_date);

    /**
     * @return DateTime|null
     */
    public function expiry_date();

    /**
     * @param string $status The publish status (can be draft, pending or published).
     * @return PublishableTrait Chainable
     */
    public function set_publish_status($status);

    /**
     * @return string
     */
    public function publish_status();

    /**
     * @return boolean
     */
    public function is_published();
}

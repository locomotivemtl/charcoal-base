<?php

namespace Charcoal\Object;

/**
 *
 */
interface PublishableInterface
{
    /**
     * @param string|DateTime $publishDate The publish date.
     * @return PublishableTrait Chainable
     */
    public function setPublishDate($publishDate);

    /**
     * @return DateTime|null
     */
    public function publishDate();

    /**
     * @param string|DateTime $expiryDate The expiry date.
     * @return PublishableTrait Chainable
     */
    public function setExpiryDate($expiryDate);

    /**
     * @return DateTime|null
     */
    public function expiryDate();

    /**
     * @param string $status The publish status (can be draft, pending or published).
     * @return PublishableTrait Chainable
     */
    public function setPublishStatus($status);

    /**
     * @return string
     */
    public function publishStatus();

    /**
     * @return boolean
     */
    public function isPublished();
}

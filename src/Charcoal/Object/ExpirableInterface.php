<?php

namespace Charcoal\Object;

/**
 * The `Expirable` mixin defines expiration properties for a model.
 *
 * The interface adds two properties:
 *
 * - `expired_on` — A timestamp property.
 * - `expired_by` — A user reference property.
 */
interface ExpirableInterface
{
    /**
     * Define whether the object is expired or scheduled to be expired
     * (with a timestamp) or not (FALSE).
     *
     * @param  DateTime|string|boolean|null $marker A timestamp for expiration or FALSE.
     * @return ExpirableInterface Chainable
     */
    public function setExpiredOn($marker);

    /**
     * Retrieve the expiration timestamp, if the object is expirable.
     *
     * @return DateTime|null
     */
    public function expiredOn();

    /**
     * Set the author of the expiration.
     *
     * @param  mixed $author The author of the expirable object.
     * @return ExpirableInterface Chainable
     */
    public function setExpiredBy($author);

    /**
     * Retrieve the author of the expirable object.
     *
     * @return mixed
     */
    public function expiredBy();

    /**
     * Determine if the object is expired.
     *
     * @return boolean
     */
    public function isExpired();
}

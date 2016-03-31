<?php

namespace Charcoal\Object;

/**
 * The `Publishable` mixin defines publication properties for a model.
 *
 * The interface adds two properties:
 *
 * - `published_on` — A timestamp property.
 * - `published_by` — A user reference property.
 * - `publication_status` — The state of the property.
 *
 * The `publication_status` property sets the terms of publishing your object.
 * There are 4 statuses used by default.
 *
 * - "draft" — Incomplete post viewable by anyone with proper permissions.
 * - "pending" — Awaiting review or a user with publishing capabilities.
 * - "published" — Publicly viewable.
 * - "scheduled" † — Scheduled to be published at a later date.
 * - "expired" † — No longer publicly viewable.
 *
 * † The `scheduled` and `expired` statuses are "magical"; when the object is set
 * to `published` but the "published_on" or "expired_on" properties do not match.
 */
interface PublishableInterface
{
    /**
     * Whether a new object should be published immediately
     * or set as a draft upon creation.
     */
    const PUBLISHED_BY_DEFAULT = true;

    /**
     * Define whether the object is published or scheduled to be published
     * (with a timestamp) or not (FALSE).
     *
     * @param  DateTime|string|boolean|null $marker A timestamp for publication or FALSE to remain unpublished.
     * @return PublishableInterface Chainable
     */
    public function setPublishedOn($marker);

    /**
     * Retrieve the publication timestamp, if the object is published.
     *
     * @return DateTime|null
     */
    public function publishedOn();

    /**
     * Set the author of the publication.
     *
     * @param  mixed $author The author of the publishable object.
     * @return PublishableInterface Chainable
     */
    public function setPublishedBy($author);

    /**
     * Retrieve the author of the publication.
     *
     * @return mixed
     */
    public function publishedBy();

    /**
     * Set the publication status of the object.
     *
     * @param  string $status The publication status.
     * @return PublishableInterface Chainable
     */
    public function setPublicationStatus($status);

    /**
     * Retrieve the publication status of the object.
     *
     * @return string
     */
    public function publicationStatus();

    /**
     * Determine if the object has been published.
     *
     * @return boolean
     */
    public function isPublished();
}

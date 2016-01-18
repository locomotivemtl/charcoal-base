<?php

namespace Charcoal\Object;

/**
 * Routable objects are loadable from a URL.
 */
interface RoutableInterface
{
    /**
     * @param boolean $routable The routable flag.
     * @return RoutableInterface Chainable
     */
    public function setRoutable($routable);

    /**
     * @return boolean
     */
    public function routable();

    /**
     * @param mixed $pattern The slug pattern.
     * @return RoutableInterface Chainable
     */
    public function setSlugPattern($pattern);

    /**
     * @return string
     */
    public function slugPattern();

    /**
     * @param mixed $slug The slug.
     * @return RoutableInterface Chainable
     */
    public function setSlug($slug);

    /**
     * @return string
     */
    public function slug();

    /**
     * Generate a URL slug from the object's URL slug pattern.
     *
     * @return string
     */
    public function generateSlug();

    /**
     * @return string
     */
    public function url();
}

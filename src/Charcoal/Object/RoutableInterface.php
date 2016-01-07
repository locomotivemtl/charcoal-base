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
    public function set_routable($routable);

    /**
     * @return boolean
     */
    public function routable();

    /**
     * @param mixed $pattern The slug pattern.
     * @return RoutableInterface Chainable
     */
    public function set_slug_pattern($pattern);

    /**
     * @return string
     */
    public function slug_pattern();

    /**
     * @param mixed $slug The slug.
     * @return RoutableInterface Chainable
     */
    public function set_slug($slug);

    /**
     * @return string
     */
    public function slug();

    /**
     * Generate a URL slug from the object's URL slug pattern.
     *
     * @return string
     */
    public function generate_slug();

    /**
     * @return string
     */
    public function url();
}

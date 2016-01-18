<?php

namespace Charcoal\Object;

// Dependencies from `charcoal-view` module
use \Charcoal\View\Viewable;

use \Charcoal\Translation\TranslationString;

/**
* Full implementation, as Trait, of the `RoutableInterface`.
*/
trait RoutableTrait
{
    /**
     * @var boolean routable
     */
    private $routable = true;

    /**
     * @var string
     */
    private $slugPattern = '';

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @param boolean $routable The routable flag.
     * @return RoutableInterface Chainable
     */
    public function setRoutable($routable)
    {
        $this->routable = !!$routable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function routable()
    {
        return $this->routable;
    }

    /**
     * @param mixed $pattern The slug pattern.
     * @return RoutableInterface Chainable
     */
    public function setSlugPattern($pattern)
    {
        $this->slugPattern = new TranslationString($pattern);
        return $this;
    }

    /**
     * @return TranslationString
     */
    public function slugPattern()
    {
        return $this->slugPattern;
    }

    /**
     * @param mixed $slug The slug.
     * @return RoutableInterface Chainable
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function slug()
    {
        if ($this->slug === null) {
            $this->slug = $this->generateSlug();
        }
        return $this->slug;
    }

    /**
     * Generate a URL slug from the object's URL slug pattern.
     *
     * @return string
     */
    public function generateSlug()
    {
        $pattern = $this->slugPattern();
        if ($this instanceof Viewable) {
            $slug = $this->render($pattern);
        } else {
            $slug = $pattern;
        }
        return $slug;
    }

    /**
     * @return string
     */
    public function url()
    {
        return $this->slug();
    }
}

<?php

namespace Charcoal\Object;

use \Charcoal\View\Viewable;
use \Charcoal\Translation\TranslationString;

/**
 * Full implementation, as Trait, of the `RoutableInterface`.
 */
trait RoutableTrait
{
    /**
     * @var string
     */
    private $slugPattern = '';

    /**
     * @var string $slug
     */
    private $slug;

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
        if (!$this->slugPattern) {
            $metadata = $this->medatada();
            return isset($metadata['slug_pattern']) ? $metadata['slug_pattern'] : '';
        }
        return $this->slugPattern;

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

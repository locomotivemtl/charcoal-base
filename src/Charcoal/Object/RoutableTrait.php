<?php

namespace Charcoal\Object;

use \Charcoal\View\ViewableInterface;
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
            $metadata = $this->metadata();
            return isset($metadata['slug_pattern']) ? $metadata['slug_pattern'] : '';
        }
        return $this->slugPattern;
    }

    /**
     * @param mixed $slug The slug.
     * @return RoutableInterface Chainable
     */
    public function setSlug($slug)
    {
        $this->slug = new TranslationString($slug);
        return $this;
    }

    /**
     * @return string
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * Generate a URL slug from the object's URL slug pattern.
     *
     * @return string
     */
    public function generateSlug()
    {
        $patterns = $this->slugPattern();
        $patterns = $patterns->all();
        $slug = [];

        foreach ($patterns as $lang => $pattern) {
            if ($this instanceof ViewableInterface && $this->view() !== null) {
                $slug[$lang] = $this->view()->render($pattern, $this->viewController());
            } else {
                $obj = $this;

                $cb = function ($matches) use ($obj) {
                    $method = trim($matches[1]);
                    if (method_exists($obj, $method)) {
                        return call_user_func([$obj, $method]);
                    } elseif (isset($obj[$method])) {
                        return $obj[$method];
                    } else {
                        return '';
                    }
                };
                $slug[$lang] = preg_replace_callback('~{{(.*?)}}~i', $cb, $pattern);
            }
        }

        return $slug;
    }



    /**
     * @return string
     */
    public function url()
    {
        return (string)$this->slug();
    }
}

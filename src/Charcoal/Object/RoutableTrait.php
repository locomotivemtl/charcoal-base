<?php

namespace Charcoal\Object;

use \Charcoal\View\ViewableInterface;
use \Charcoal\Translation\TranslationString;
use \Charcoal\Translation\TranslationConfig;

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
            $slugPattern = isset($metadata['slug_pattern']) ? $metadata['slug_pattern'] : '';
            $this->setSlugPattern($slugPattern);
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
        $patterns = $this->slug->all();
        foreach ($patterns as $lang => $pattern) {
            $this->slug[$lang] = $this->slugify($this->slug[$lang]);
        }
        return $this;
    }

    /**
     * @return TranslationString
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * Generate a URL slug from the object's URL slug pattern.
     *
     * @return TranslationString
     */
    public function generateSlug()
    {
        $patterns = $this->slugPattern();
        $patterns = $patterns->all();
        $slug = new TranslationString();

        $translator = TranslationConfig::instance();

        $origLang = $translator->currentLanguage();
        foreach ($patterns as $lang => $pattern) {
            $translator->setCurrentLanguage($lang);
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
            $slug[$lang] = $this->slugify($slug[$lang]);
        }
        $translator->setCurrentLanguage($origLang);

        return $slug;
    }

    /**
     * @return string
     */
    public function url()
    {
        return (string)$this->slug();
    }

    /**
     * @param string $str The string to slugify.
     * @return string The slugified string.
     */
    public function slugify($str)
    {
        // Character options
        $separator = '_';
        $punctuation_modifier = $separator;

        // Punctuation
        $punctuation_characters = ['&', '%', '?', ')', '(', '\\', '"', "'", ':', '#', '.', ',', ';', '!'];

        // Unescaped HTML characters string
        $unescaped_html_characters = '/&(raquo|laquo|rsaquo|lsaquo|rdquo|ldquo|rsquo|lsquo|hellip|amp|nbsp|quot|ordf|ordm);/';

        $separator = '-';

        $slug = preg_replace('/[^\p{L}\s]/u', '-', $str);

        $slug = mb_strtolower($slug, 'UTF-8');

        // Strip HTML
        $slug = strip_tags($slug);

        // Strip Whitespace
        $slug = trim($slug);

        // Remove diacritics
        $slug = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/', '$1', htmlentities($slug, ENT_COMPAT, 'UTF-8'));

        // Remove unescaped HTML characters
        $slug = preg_replace($unescaped_html_characters, '', $slug);

        // Get rid of punctuation
        $slug = str_replace($punctuation_characters, $separator, $slug);

        // Post-cleanup, get rid of spaces, repeating dash symbols symbols and surround whitespace/separators
        $slug = trim($slug);

        // Replace whitespace by seperator
        $slug = preg_replace('/\s+/', $separator, $slug);

        // Squeeze multiple dashes or underscores
        $slug = preg_replace('/[-_]{2,}/', '-', $slug);

        // Strip leading and trailing dashes or underscores
        $slug = trim($slug, '-_');

        // Finally, remove all whitespace
        $slug = preg_replace('/[_]+/', $separator, $slug);
        //$slug = str_replace('_', $separator, $slug);

        return $slug;
    }
}

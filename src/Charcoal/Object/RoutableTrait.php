<?php

namespace Charcoal\Object;

use \InvalidArgumentException;

// Dependencies from 'charcoal-core'
use \Charcoal\Loader\CollectionLoader;

// Dependencies from 'charcoal-translation'
use \Charcoal\Translation\TranslationString;
use \Charcoal\Translation\TranslationConfig;

// Dependency from 'charcoal-view'
use \Charcoal\View\ViewableInterface;

// Local Dependencies
use \Charcoal\Object\ObjectRoute;

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
     * Latest ObjectRoute object concerning the current object.
     * @var ObjectRoute $latestObjectRoute;
     */
    private $latestObjectRoute;

    /**
     * Set the object's URL slug pattern.
     *
     * @param mixed $pattern The slug pattern.
     * @return RoutableInterface Chainable
     */
    public function setSlugPattern($pattern)
    {
        if (TranslationString::isTranslatable($pattern)) {
            $this->slugPattern = new TranslationString($pattern);
        } else {
            $this->slugPattern = null;
        }

        return $this;
    }

    /**
     * Retrieve the object's URL slug pattern.
     *
     * @throws InvalidArgumentException If a slug pattern is not defined.
     * @return TranslationString|null
     */
    public function slugPattern()
    {
        if (!$this->slugPattern) {
            $metadata = $this->metadata();

            if (isset($metadata['routable']['pattern'])) {
                $this->setSlugPattern($metadata['routable']['pattern']);
            } elseif (isset($metadata['slug_pattern'])) {
                $this->setSlugPattern($metadata['slug_pattern']);
            } else {
                throw new InvalidArgumentException(
                    sprintf('Undefined route pattern (slug) for %s', get_called_class())
                );
            }
        }

        return $this->slugPattern;
    }

    /**
     * Set the object's URL slug.
     *
     * @param mixed $slug The slug.
     * @return RoutableInterface Chainable
     */
    public function setSlug($slug)
    {
        if (TranslationString::isTranslatable($slug)) {
            $this->slug = new TranslationString($slug);

            $values = $this->slug->all();
            foreach ($values as $lang => $val) {
                $this->slug[$lang] = $this->slugify($val);
            }
        } else {
            /** @todo Hack used for regenerating route */
            if (isset($_POST['slug'])) {
                $this->slug = [];
            } else {
                $this->slug = null;
            }
        }

        return $this;
    }

    /**
     * Retrieve the object's URL slug.
     *
     * @return TranslationString|null
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
        $translator = TranslationConfig::instance();
        $patterns   = $this->slugPattern();
        $curSlug    = $this->slug();
        $newSlug    = new TranslationString();

        if ($patterns instanceof TranslationString) {
            $patterns = $patterns->all();
        }

        $origLang = $translator->currentLanguage();
        foreach ($patterns as $lang => $pattern) {
            if (!$translator->hasLanguage($lang)) {
                continue;
            }

            $translator->setCurrentLanguage($lang);

            if (isset($curSlug[$lang]) && strlen($curSlug[$lang])) {
                $newSlug[$lang] = $curSlug[$lang];
            } else {
                $newSlug[$lang] = $this->generateRoutePattern($pattern);
            }

            $objectRoute = $this->modelFactory()->create(ObjectRoute::class);
            if ($objectRoute->source()->tableExists()) {
                $objectRoute->setData([
                    'lang'           => $lang,
                    'slug'           => $newSlug[$lang],
                    'route_obj_type' => $this->objType(),
                    'route_obj_id'   => $this->id()
                ]);

                if (!$objectRoute->isSlugUnique()) {
                    $objectRoute->generateUniqueSlug();
                    $newSlug[$lang] = $objectRoute->slug();
                }
            }
        }
        $translator->setCurrentLanguage($origLang);

        return $newSlug;
    }

    /**
     * Generate a route from the given pattern.
     *
     * @uses   self::parseRouteToken() If a view renderer is unavailable.
     * @param  string $pattern The slug pattern.
     * @return string Returns the generated route.
     */
    protected function generateRoutePattern($pattern)
    {
        if ($this instanceof ViewableInterface && $this->view() !== null) {
            $route = $this->view()->render($pattern, $this->viewController());
        } else {
            $route = preg_replace_callback('~\{\{\s*(.*?)\s*\}\}~i', [ $this, 'parseRouteToken' ], $pattern);
        }

        return $this->slugify($route);
    }

    /**
     * Parse the given slug (URI token) for the current object.
     *
     * @used-by self::generateRoutePattern() If a view renderer is unavailable.
     * @uses    self::filterRouteToken() For customize the route value filtering,
     * @param   string|array $token The token to parse relative to the model entry.
     * @throws  InvalidArgumentException If a route token is not a string.
     * @return  string
     */
    protected function parseRouteToken($token)
    {
        // Processes matches from a regular expression operation
        if (is_array($token) && isset($token[1])) {
            $token = $token[1];
        }

        $token  = trim($token);
        $method = [ $this, $token ];

        if (is_callable($method)) {
            $value = call_user_func($method);
        /** @see \Charcoal\Config\AbstractEntity::offsetGet() */
        } elseif (isset($this[$token])) {
            $value = $this[$token];
        } else {
            return '';
        }

        $value = $this->filterRouteToken($value, $token);
        if (!is_string($value) && !is_numeric($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Route token "%1$s" must be a string with %2$s',
                    $token,
                    get_called_class()
                )
            );
        }

        return $value;
    }

    /**
     * Filter the given value for a URI.
     *
     * @used-by self::parseRouteToken() To resolve the token's value.
     * @param   mixed  $value A value to filter.
     * @param   string $token The parsed token.
     * @return  string The filtered $value.
     */
    protected function filterRouteToken($value, $token = null)
    {
        unset($token);

        if ($value instanceof \Closure) {
            $value = $value();
        }

        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d-H:i');
        }

        return $value;
    }

    /**
     * Route generation.
     *
     * Saves all routes to {@see ObjectRoute}.
     *
     * @param  mixed $slug Slug by langs.
     * @return void
     */
    protected function generateObjectRoute($slug = null)
    {
        $translator = TranslationConfig::instance();

        if (!$slug) {
            $slug = $this->generateSlug();
        }

        if ($slug instanceof TranslationString) {
            $slugs = $slug->all();
        }

        $origLang = $translator->currentLanguage();
        foreach ($slugs as $lang => $slug) {
            if (!$translator->hasLanguage($lang)) {
                continue;
            }

            $translator->setCurrentLanguage($lang);

            $objectRoute = $this->modelFactory()->create(ObjectRoute::class);

            $source = $objectRoute->source();
            if (!$source->tableExists()) {
                $source->createTable();
            } else {
                $oldRoute = $this->getLatestObjectRoute();

                // Unchanged
                if ($slug === $oldRoute->slug()) {
                    continue;
                }
            }

            $objectRoute->setData([
                'lang'           => $lang,
                'slug'           => $slug,
                'route_obj_type' => $this->objType(),
                'route_obj_id'   => $this->id(),
                // Not used, might be too much.
                'route_template' => $this->templateIdent(),
                'active'         => true
            ]);

            if (!$objectRoute->isSlugUnique()) {
                $objectRoute->generateUniqueSlug();
            }

            if ($objectRoute->id()) {
                $objectRoute->update();
            } else {
                $objectRoute->save();
            }
        }
        $translator->setCurrentLanguage($origLang);
    }

    /**
     * Retrieve the latest object route.
     *
     * @param  string|null $lang If object is multilingual, return the object route for the specified locale.
     * @throws InvalidArgumentException If the given language is invalid.
     * @return ObjectRoute Latest object route.
     */
    protected function getLatestObjectRoute($lang = null)
    {
        $translator = TranslationConfig::instance();

        if ($lang === null) {
            $lang = $translator->currentLanguage();
        } elseif (!$translator->hasLanguage($lang)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid language, received %s',
                    (is_object($lang) ? get_class($lang) : gettype($lang))
                )
            );
        }

        if (isset($this->latestObjectRoute[$lang])) {
            return $this->latestObjectRoute[$lang];
        }

        $model = $this->modelFactory()->create(ObjectRoute::class);

        if (!$this->objType() || !$this->id()) {
            $this->latestObjectRoute[$lang] = $model;

            return $this->latestObjectRoute[$lang];
        }

        // For URL.
        $source = $model->source();
        $loader = new CollectionLoader([
            'logger'  => $this->logger,
            'factory' => $this->modelFactory()
        ]);

        if (!$source->tableExists()) {
            $source->createTable();
        }

        $loader
            ->setModel($model)
            ->addFilter('route_obj_type', $this->objType())
            ->addFilter('route_obj_id', $this->id())
            ->addFilter('lang', $lang)
            ->addFilter('active', true)
            ->addOrder('creation_date', 'desc')
            ->setPage(1)
            ->setNumPerPage(1);

        $collection = $loader->load()->objects();

        if (!count($collection)) {
            $this->latestObjectRoute[$lang] = $model;

            return $this->latestObjectRoute[$lang];
        }

        $this->latestObjectRoute[$lang] = $collection[0];

        return $this->latestObjectRoute[$lang];
    }

    /**
     * Retrieve the object's URI.
     *
     * @param  string|null $lang If object is multilingual, return the object route for the specified locale.
     * @return TranslationString|string
     */
    public function url($lang = null)
    {
        $url = (string)$this->getLatestObjectRoute($lang)->slug();
        if ($url) {
            return $url;
        }

        $slug = $this->slug();

        if ($slug instanceof TranslationString && $lang) {
            return $slug->val($lang);
        }

        return (string)$slug;
    }

    /**
     * @param string $str The string to slugify.
     * @return string The slugified string.
     */
    public function slugify($str)
    {
        static $sluggedArray;

        if (isset($sluggedArray[$str])) {
            return $sluggedArray[$str];
        }

        $metadata    = $this->metadata();
        $separator   = isset($metadata['routable']['separator']) ? $metadata['routable']['separator'] : '-';
        $delimiters  = '-_|';
        $pregDelim   = preg_quote($delimiters);
        $directories = '\\/';
        $pregDir     = preg_quote($directories);

        // Do NOT remove forward slashes.
        $slug = preg_replace('![^(\p{L}|\p{N})(\s|\/)]!u', $separator, $str);

        if (!isset($metadata['routable']['lowercase']) || $metadata['routable']['lowercase'] === false) {
            $slug = mb_strtolower($slug, 'UTF-8');
        }

        // Strip HTML
        $slug = strip_tags($slug);

        // Remove diacritics
        $slug = preg_replace(
            '!&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);!',
            '$1',
            htmlentities($slug, ENT_COMPAT, 'UTF-8')
        );

        // Remove unescaped HTML characters
        $unescaped = '!&(raquo|laquo|rsaquo|lsaquo|rdquo|ldquo|rsquo|lsquo|hellip|amp|nbsp|quot|ordf|ordm);!';
        $slug = preg_replace($unescaped, '', $slug);

        // Unify all dashes/underscores as one separator character
        $flip = ($separator === '-') ? '_' : '-';
        $slug = preg_replace('!['.preg_quote($flip).']+!u', $separator, $slug);

        // Remove all whitespace and normalize delimiters
        $slug = preg_replace('![_\|\s]+!', $separator, $slug);

        // Squeeze multiple delimiters and whitespace with a single separator
        $slug = preg_replace('!['.$pregDelim.'\s]{2,}!', $separator, $slug);

        // Squeeze multiple URI path delimiters
        $slug = preg_replace('!['.$pregDir.']{2,}!', $separator, $slug);

        // Remove delimiters surrouding URI path delimiters
        $slug = preg_replace('!(?<=['.$pregDir.'])['.$pregDelim.']|['.$pregDelim.'](?=['.$pregDir.'])!', '', $slug);

        // Strip leading and trailing dashes or underscores
        $slug = trim($slug, $delimiters);

        $sluggedArray[$str] = $slug;

        if (isset($metadata['routable']['prefix'])) {
            $slug = $metadata['routable']['prefix'].$slug;
        }

        return $slug;
    }

    /**
     * Delete all object routes.
     *
     * Should be called on object deletion {@see \Charcoal\Model\AbstractModel::preDelete()}.
     *
     * @return boolean Success or failure.
     */
    protected function deleteObjectRoutes()
    {
        if (!$this->objType()) {
            return false;
        }

        if (!$this->id()) {
            return false;
        }

        $model  = $this->modelFactory()->get(ObjectRoute::class);
        $loader = new CollectionLoader([
            'logger'  => $this->logger,
            'factory' => $this->modelFactory()
        ]);

        $loader
            ->setModel($model)
            ->addFilter('route_obj_type', $this->objType())
            ->addFilter('route_obj_id', $this->id());

        $collection = $loader->load();
        foreach ($collection as $route) {
            $route->delete();
        }

        return true;
    }

    /**
     * Retrieve the object model factory.
     *
     * @return \Charcoal\Factory\FactoryInterface
     */
    abstract public function modelFactory();

    /**
     * Retrieve the routable object's template identifier.
     *
     * @return mixed
     */
    abstract public function templateIdent();
}

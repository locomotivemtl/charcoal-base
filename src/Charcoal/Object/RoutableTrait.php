<?php

namespace Charcoal\Object;

use \Charcoal\View\ViewableInterface;
use \Charcoal\Translation\TranslationString;
use \Charcoal\Translation\TranslationConfig;

use \Charcoal\Loader\CollectionLoader;
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
     * Route generation. Saves all route to one table.
     * @param  mixed $slug Slug by langs.
     * @return void
     */
    protected function generateObjectRoute($slug = null)
    {
        if (!$slug) {
            $slug = $this->generateSlug();
        }

        // We NEED a translationString here.
        $slugs = $slug->all();
        foreach ($slugs as $lang => $slug) {
            $objectRoute = $this->modelFactory()->create(ObjectRoute::class);

            $source = $objectRoute->source();
            if (!$source->tableExists()) {
                $source->createTable();
            }

            // Try to EDIT current
            $objectRoute->loadFrom('slug', $slug);

            if (!$objectRoute->isSlugUnique()) {
                $objectRoute->generateUniqueSlugFromCurrentObjectSlug();
            }

            $data = [
                'lang' => $lang,
                'slug' => $slug,
                'route_obj_type' => $this->objType(),
                'route_obj_id' => $this->id(),
                // Not used, might be too much.
                'route_template' => $this->templateIdent(),
                'active' => true
            ];

            $objectRoute->setData($data);

            if ($objectRoute->id()) {
                $objectRoute->update();
            } else {
                $objectRoute->save();
            }
        }
    }

    /**
     * Get the latest object route.
     * @return ObjectRoute Latest object route.
     */
    protected function getLatestObjectRoute()
    {
        if ($this->latestObjectRoute) {
            return $this->latestObjectRoute;
        }

        // For URL.
        $loader = new CollectionLoader([
            'logger' => $this->logger,
            'factory' => $this->modelFactory()
        ]);

        $model = $this->modelFactory()->create(ObjectRoute::class);
        $loader->setModel($model);

        $translator = new TranslationString();

        $loader->addFilter('route_obj_type', $this->objType())
            ->addFilter('route_obj_id', $this->id())
            ->addFilter('lang', $translator->currentLanguage())
            ->addOrder('creation_date', 'desc')
            ->setPage(1)
            ->setNumPerPage(1);

        $collection = $loader->load()->objects();

        if (!count($collection)) {
            $this->latestObjectRoute = $model;
            return $this->latestObjectRoute;
        }
        $this->latestObjectRoute = $collection[0];

        return $this->latestObjectRoute;
    }

    /**
     * @return string
     */
    public function url()
    {
        $url = (string)$this->getLatestObjectRoute()->slug();
        if ($url) {
            return $url;
        }
        return (string)$this->slug();
    }

    /**
     * @param string $str The string to slugify.
     * @return string The slugified string.
     */
    public function slugify($str)
    {
        // Do NOT remove forward slashes.
        $slug = preg_replace('/[^(\p{L}|\p{N})(\s|\/)]/u', '-', $str);

        $slug = mb_strtolower($slug, 'UTF-8');

        // Strip HTML
        $slug = strip_tags($slug);

        // Remove diacritics
        $slug = preg_replace(
            '/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/',
            '$1',
            htmlentities($slug, ENT_COMPAT, 'UTF-8')
        );

        // Remove unescaped HTML characters
        $unescaped = '/&(raquo|laquo|rsaquo|lsaquo|rdquo|ldquo|rsquo|lsquo|hellip|amp|nbsp|quot|ordf|ordm);/';
        $slug = preg_replace($unescaped, '', $slug);

        // Replace whitespace by seperator
        $slug = preg_replace('/\s+/', '-', $slug);

        // Squeeze multiple dashes or underscores
        $slug = preg_replace('/[-]{2,}/', '-', $slug);

        // Strip leading and trailing dashes or underscores
        $slug = trim($slug, '-');

        // Finally, remove all whitespace
        $slug = preg_replace('/[_]+/', '-', $slug);

        return $slug;
    }

    /**
     * Delete all object routes. Should be called
     * on object suppression.
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

        $loader = new CollectionLoader([
            'logger' => $this->logger,
            'factory' => $this->modelFactory()
        ]);

        $model = $this->modelFactory()->create(ObjectRoute::class);
        $loader->setModel($model);

        $loader->addFilter('route_obj_type', $this->objType())
            ->addFilter('route_obj_id', $this->id());

        $collection = $loader->load();
        foreach ($collection as $route) {
            $route->delete();
        }

        return true;
    }

    /**
     * Requires the modelFactory in order to generate the
     * according objectRoute.
     * @return FactoryInterface ModelFactory
     */
    abstract protected function modelFactory();

    /**
     * The template ident defines the template
     * used to view the current object.
     * @return string ident/of/the/template
     */
    abstract public function templateIdent();
}

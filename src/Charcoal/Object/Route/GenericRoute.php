<?php

namespace Charcoal\Object\Route;

use \Exception;

// PSR-7 (http messaging) dependencies
use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;

// Dependencies from `Pimple`
use \Pimple\Container;

// Dependency from 'charcoal-app'
use \Charcoal\App\Route\TemplateRoute;

// From `charcoal-base`
use \Charcoal\Object\RoutableInterface;

// From `charcoal-translation`
use \Charcoal\Translation\TranslationConfig;

// Route object
use \Charcoal\Object\ObjectRoute;

/**
 * Generic route.
 * Uses the ObjectRoute object to match routes in
 * a catchall.
 */
class GenericRoute extends TemplateRoute
{
    /**
     * Charcoal\Object\ObjectRoute
     * @var object $objectRoute
     */
    private $objectRoute;

    /**
     * @var string $path
     */
    private $path;

    /**
     * CollectionLoader from dependencie container.
     * @var CollectionLoader $collectionLoader
     */
    private $collectionLoader;

    /**
     * ModelFactory from dependencie container;
     * @var ModelFactory $modelFactory
     */
    private $modelFactory;

    /**
     * Mixed object
     * @var Object $contextObject mixed.
     */
    private $contextObject;

    /**
     * @param array|\ArrayAccess $data Class depdendencies.
     */
    public function __construct($data)
    {
        parent::__construct($data);

        $this->setPath(ltrim($data['path'], '/'));
    }

    public function setDependencies(Container $container)
    {
        // Dependencies.
        $this->setModelFactory($container['model/factory']);
        $this->setCollectionLoader($container['model/collection/loader']);
    }

    /**
     * @param  Container $container A DI (Pimple) container.
     * @return boolean
     */
    public function pathResolvable(Container $container)
    {
        $this->setDependencies($container);
        $object = $this->loadObjectRouteFromPath();
        if (!$object->id()) {
            return false;
        }

        $contextObject = $this->loadContextObject();

        if (!$contextObject || !$contextObject->id()) {
            return false;
        }

        return !!$contextObject->active();
    }

    /**
     * @param  Container         $container A DI (Pimple) container.
     * @param  RequestInterface  $request   A PSR-7 compatible Request instance.
     * @param  ResponseInterface $response  A PSR-7 compatible Response instance.
     * @return ResponseInterface
     */
    public function __invoke(Container $container, RequestInterface $request, ResponseInterface $response)
    {
        $config = $this->config();
        $this->setDependencies($container);

        $objectRoute = $this->loadObjectRouteFromPath();
        $contextObject = $this->loadContextObject();

        TranslationConfig::instance()->setCurrentLanguage($objectRoute->lang());


        $templateIdent      = (string)$contextObject->templateIdent();
        $templateController = (string)$contextObject->templateIdent();

        $templateFactory = $container['template/factory'];
        $templateFactory->setDefaultClass($config['default_controller']);

        $template = $templateFactory->create($templateController);
        $template->init($request);

        // Set custom data from config.
        $template->setData($config['template_data']);
        $template->setContextObject($contextObject);

        $templateContent = $container['view']->render($templateIdent, $template);

        $response->write($templateContent);

        return $response;
    }

    /**
     * Load the context object.
     * @return RoutableInterface Routable object.
     */
    protected function loadContextObject()
    {
        if ($this->contextObject) {
            return $this->contextObject;
        }

        // Path set in constructor
        // Factories set above
        // We do not check if the ID exists, this is the
        // pathResolvable() method job.
        $objectRoute = $this->loadObjectRouteFromPath();

        // Could be the SAME
        $latest = $this->getLatestObjectPathHistory($objectRoute);

        if ($latest->creationDate() > $objectRoute->creationDate()) {
            $objectRoute = $latest;
            // Redirect 302
        }

        $this->contextObject = $this->modelFactory()->create($objectRoute->routeObjType())->load($objectRoute->routeObjId());

        return $this->contextObject;
    }

    /**
     * @return \Charcoal\Object\ObjectRoute
     */
    protected function loadObjectRouteFromPath()
    {
        if ($this->objectRoute) {
            return $this->objectRoute;
        }

        // Load current slug
        // Slug are uniq
        $mFactory = $this->modelFactory();
        $obj = $mFactory->create(ObjectRoute::class);
        $obj->loadFrom('slug', $this->path());

        $this->objectRoute = $obj;

        return $this->objectRoute;
    }

    /**
     * Get the latest path history for the given object order
     * by creationDate DESC (latest first.)
     * Should never MISS, the given object route should exist.
     * @param  Charcoal\Object\ObjectRoute $obj Routable Object.
     * @return Charcoal\Object\ObjectRoute        Latest route.
     */
    public function getLatestObjectPathHistory($obj)
    {
        // Check if current objType and ID have a more recent route.
        $objectType = $obj->routeObjType();
        $objectId = $obj->routeObjId();
        $lang = $obj->lang();

        $loader = $this->collectionLoader();
        $loader->setModel($obj);

        $loader
            ->addFilter('active', true)
            ->addFilter('route_obj_type', $objectType)
            ->addFilter('route_obj_id', $objectId)
            ->addFilter('lang', $lang)
            ->addOrder('creation_date', 'desc')
            ->setPage(1)
            ->setNumPerPage(1);

        $collection = $loader->load();
        $objects = $collection->objects();

        $verifyObject = $objects[0];

        return $verifyObject;
    }

/**
 * SETTERS
 */
    protected function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    protected function setModelFactory($modelFactory)
    {
        $this->modelFactory = $modelFactory;
        return $this;
    }
    public function setCollectionLoader($loader)
    {
        $this->collectionLoader = $loader;
        return $this;
    }
/**
 * GETTERS
 */
    protected function path()
    {
        return $this->path;
    }
    protected function modelFactory()
    {
        return $this->modelFactory;
    }
    protected function collectionLoader()
    {
        return $this->collectionLoader;
    }
}

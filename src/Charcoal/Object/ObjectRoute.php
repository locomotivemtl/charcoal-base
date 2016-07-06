<?php
namespace Charcoal\Object;

use \Pimple\Container;

use \DateTime;
use \InvalidArgumentException;

// From `charcoal-core`
use \Charcoal\Model\AbstractModel;
use \Charcoal\Factory\FactoryInterface;

/**
 * ObjectRoute object class.
 * Used as a single table for all routable objects.
 * See Charcoal\Object\RevisionableTrait
 */
class ObjectRoute extends AbstractModel
{
    /**
     * Base.
     * @var number $id
     */
    protected $id;

    /**
     * Route active?
     * @var boolean $active
     */
    protected $active;

    /**
     * Uniq.
     * @var string $slug
     */
    protected $slug;

    /**
     * Route lang ident.
     * @var string $lang
     */
    protected $lang;

    /**
     * Used for revisions, redirects, etc.
     * @var DateTime $creationDate
     */
    protected $creationDate;

    /**
     * Last modification date. More of
     * a log utils function. Not used in the
     * genericRoute.
     * @var DateTime $lastModificationDate
     */
    protected $lastModificationDate;

    /**
     * Object type.
     * @var string $routeObjType
     */
    protected $routeObjType;

    /**
     * Object ID.
     * @var mixed $routeObjId
     */
    protected $routeObjId;

    /**
     * Route template ident.
     * @var string $routeTemplate
     */
    protected $routeTemplate;

    /**
     * ModelFactory
     * @var FactoryInterface $modelFactory
     */
    protected $modelFactory;

    /**
     * @var string $originalSlug
     */
    private $originalSlug;

    /**
     * Increment var used to create a uniq slug.
     * @var integer $slugInc
     */
    private $slugInc = 0;

    /**
     * Set dependencies
     * @param Container $container Set dependencies.
     * @return void
     */
    public function setDependencies(Container $container)
    {
        $this->setModelFactory($container['model/factory']);
    }

    /**
     * Creation date must be set
     * @return void .
     */
    public function preSave()
    {
        $this->generateUniqueSlugFromCurrentObjectSlug();
        $this->setCreationDate('now');
        $this->setLastModificationDate('now');

        parent::preSave();
    }

    /**
     * Creation date must be updated.
     * Update shouldn't happen tho.
     * @param array $properties Properties.
     * @return void .
     */
    public function preUpdate(array $properties = null)
    {
        $this->setCreationDate('now');
        $this->setLastModificationDate('now');
        parent::preUpdate($properties);
    }

    /**
     * [isSlugUnique description]
     * @return boolean [description]
     */
    public function isSlugUnique()
    {
        $obj = $this->modelFactory()->create(self::class);
        $obj->loadFrom('slug', $this->slug());

        if ($obj->id() && $obj->id() != $this->id()) {
            // Problem.
            return false;
        }

        return true;
    }

    /**
     * Sets the slug to a unique slug.
     * @return ObjectRoute Chainable
     */
    public function generateUniqueSlugFromCurrentObjectSlug()
    {
        if ($this->isSlugUnique()) {
            return $this;
        }

        // Remember.
        if (!$this->originalSlug) {
            $this->originalSlug = $this->slug();
        }
        $this->slugInc++;

        $this->setSlug($this->slug().'-'.$this->slugInc++);

        if (!$this->isSlugUnique()) {
            return $this->generateUniqueSlugFromCurrentObjectSlug();
        }

        return $this;
    }

/**
 * SETTERS
 */
    /**
     * Set the slug of the current route.
     * @param string $slug Current object / lang slug.
     * @return ObjectRoute Chainable.
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
    /**
     * Set the lang of the current route.
     * @param string $l Current lang.
     * @return ObjectRoute Chainable.
     */
    public function setLang($l)
    {
        $this->lang = $l;
        return $this;
    }

    /**
     * @param \DateTime|string|null $creationDate The Creation Date date/time.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return ObjectRoute Chainable
     */
    public function setCreationDate($creationDate)
    {
        if ($creationDate === null) {
            $this->creationDate = null;
            return $this;
        }
        if (is_string($creationDate)) {
            $creationDate = new DateTime($creationDate);
        }
        if (!($creationDate instanceof DateTime)) {
            throw new InvalidArgumentException(
                'Invalid "Creation Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->creationDate = $creationDate;
        return $this;
    }
    /**
     * @param \DateTime|string|null $lastModificationDate The Last modification date date/time.
     * @throws InvalidArgumentException If the date/time is invalid.
     * @return ObjectRoute Chainable
     */
    public function setLastModificationDate($lastModificationDate)
    {
        if ($lastModificationDate === null) {
            $this->lastModificationDate = null;
            return $this;
        }
        if (is_string($lastModificationDate)) {
            $lastModificationDate = new DateTime($lastModificationDate);
        }
        if (!($lastModificationDate instanceof DateTime)) {
            throw new InvalidArgumentException(
                'Invalid "Creation Date" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->lastModificationDate = $lastModificationDate;
        return $this;
    }

    /**
     * Set the route obj type
     * @param string $type Route object type.
     * @return ObjectRoute Chainable.
     */
    public function setRouteObjType($type)
    {
        $this->routeObjType = $type;
        return $this;
    }
    /**
     * Set the object route id
     * @param string $id Object ID.
     * @return ObjectRoute Chainable
     */
    public function setRouteObjId($id)
    {
        $this->routeObjId = $id;
        return $this;
    }
    /**
     * Set the object route template
     * @param string $template Template ident.
     * @return ObjectRoute Chainable
     */
    public function setRouteTemplate($template)
    {
        $this->routeTemplate = $template;
        return $this;
    }
    /**
     * Set the model factory.
     * @param FactoryInterface $factory Model factory.
     * @return ObjectRoute Chainable
     */
    protected function setModelFactory(FactoryInterface $factory)
    {
        $this->modelFactory = $factory;
        return $this;
    }

/**
 * GETTERS
 */
    /**
     * Slug.
     * @return string Slug.
     */
    public function slug()
    {
        return $this->slug;
    }
    /**
     * Language.
     * @return string Current language.
     */
    public function lang()
    {
        return $this->lang;
    }
    /**
     * Creation date.
     * @return DateTime Creation date.
     */
    public function creationDate()
    {
        return $this->creationDate;
    }
    /**
     * Last modification date.
     * @return DateTime Last modification date.
     */
    public function lastModificationDate()
    {
        return $this->lastModificationDate;
    }
    /**
     * Route object type.
     * @return string Route Object Type.
     */
    public function routeObjType()
    {
        return $this->routeObjType;
    }
    /**
     * Route Object ID.
     * @return string Route object id.
     */
    public function routeObjId()
    {
        return $this->routeObjId;
    }
    /**
     * Route template.
     * @return string Route template.
     */
    public function routeTemplate()
    {
        return $this->routeTemplate;
    }
    /**
     * ModelFactory.
     * @return FactoryInterface Model factory.
     */
    protected function modelFactory()
    {
        return $this->modelFactory;
    }
}

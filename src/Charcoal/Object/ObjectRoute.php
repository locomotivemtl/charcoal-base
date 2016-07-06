<?php
namespace Charcoal\Object;

use \Pimple\Container;

use \DateTime;
use \InvalidArgumentException;

// From `charcoal-core`
use \Charcoal\Model\AbstractModel;

/**
 * ObjectRoute object class.
 * Used as a single table for all routable objects.
 * @see Charcoal\Object\RevisionableTrait
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
    private $slugInc = 0;

    /**
     * Set dependencies
     * @param Container $container Set dependencies.
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
     * @param  array|null $properties [description]
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
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
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

    public function setRouteObjType($type)
    {
        $this->routeObjType = $type;
        return $this;
    }
    public function setRouteObjId($id)
    {
        $this->routeObjId = $id;
        return $this;
    }
    public function setRouteTemplate($template)
    {
        $this->routeTemplate = $template;
        return $this;
    }
    protected function setModelFactory($factory)
    {
        $this->modelFactory = $factory;
        return $this;
    }

/**
 * GETTERS
 */
    public function slug()
    {
        return $this->slug;
    }
    public function lang()
    {
        return $this->lang;
    }
    public function creationDate()
    {
        return $this->creationDate;
    }
    public function lastModificationDate()
    {
        return $this->lastModificationDate;
    }
    public function routeObjType()
    {
        return $this->routeObjType;
    }
    public function routeObjId()
    {
        return $this->routeObjId;
    }
    public function routeTemplate()
    {
        return $this->routeTemplate;
    }
    protected function modelFactory()
    {
        return $this->modelFactory;
    }
}

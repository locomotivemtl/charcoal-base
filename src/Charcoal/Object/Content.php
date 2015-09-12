<?php

namespace Charcoal\Object;

use \DateTime as DateTime;
use \InvalidArgumentException as InvalidArgumentException;

use \Charcoal\Object\AbstractObject as AbstractObject;
use \Charcoal\Object\ContentInterface as ContentInterface;

class Content extends AbstractObject implements ContentInterface
{
    /**
    * Objects are active by default
    * @var boolean $_active
    */
    private $active = true;

    /**
    * The position is used for ordering lists
    * @var integer $_position
    */
    private $position = 0;

    /**
    * Object creation date (set automatically on save)
    * @var DateTime $_created
    */
    private $created;
    
    /**
    * @var mixed
    */
    private $created_by;

    /**
    * Object last modified date (set automatically on save and update)
    * @var DateTime $_last_modified
    */
    private $last_modified;

    /**
    * @var mixed
    */
    private $last_modified_by;

    /**
    * @param boolean $active
    * @throws InvalidArgumentException
    * @return Content Chainable
    */
    public function set_active($active)
    {
        $this->active = !!$active;
        return $this;
    }

    /**
    * @return boolean
    */
    public function active()
    {
        return $this->active;
    }

    /**
    * @param integer $position
    * @throws InvalidArgumentException
    * @return Content Chainable
    */
    public function set_position($position)
    {
        if ($position === null) {
            $this->position = null;
            return $this;
        }
        if (!is_numeric($position)) {
            throw new InvalidArgumentException('Position must be an integer.');
        }
        $this->position = (int)$position;
        return $this;
    }

    /**
    * @return integer
    */
    public function position()
    {
        return $this->position;
    }

    /**
    * @param DateTime|string $created
    * @throws InvalidArgumentException
    * @return Content Chainable
    */
    public function set_created($created)
    {
        if($created === null) {
            $this->created = null;
            return $this;
        }
        if (is_string($created)) {
            $created = new DateTime($created);
        }
        if (!($created instanceof DateTime)) {
            throw new InvalidArgumentException(
                'Invalid "Created" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->created = $created;
        return $this;
    }

    /**
    * @return DateTime|null
    */
    public function created()
    {
        return $this->created;
    }

    /**
    * @param mixed $created_by
    * @return Content Chainable
    */
    public function set_created_by($created_by)
    {
        $this->created_by = $created_by;
        return $this;
    }

    /**
    * @return mixed
    */
    public function created_by()
    {
        return $this->created_by;
    }

    /**
    * @param DateTime|string $last_modified
    * @throws InvalidArgumentException
    * @return Content Chainable
    */
    public function set_last_modified($last_modified)
    {
        if($last_modified === null) {
            $this->last_modified = null;
            return $this;
        }
        if (is_string($last_modified)) {
            $last_modified = new DateTime($last_modified);
        }
        if (!($last_modified instanceof DateTime)) {
            throw new InvalidArgumentException(
                'Invalid "Last Modified" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->last_modified = $last_modified;
        return $this;
    }

    /**
    * @return DateTime
    */
    public function last_modified()
    {
        return $this->last_modified;
    }

    /**
    * @param mixed $last_modified_by
    * @return Content Chainable
    */
    public function set_last_modified_by($last_modified_by)
    {
        $this->last_modified_by = $last_modified_by;
        return $this;
    }

    /**
    * @return mixed
    */
    public function last_modified_by()
    {
        return $this->last_modified_by;
    }

    /**
    * StorableTrait > pre_save(): Called automatically before saving the object to source.
    * For content object, set the `created` and `last_modified` properties automatically
    * @return bool
    */
    public function pre_save()
    {
        parent::pre_save();
        $this->set_created('now');
        $this->set_last_modified('now');
        return true;
    }

    /**
    * StorableTrait > pre_update(): Called automatically before updating the object to source.
    * For content object, set the `last_modified` property automatically.
    * @param array $properties
    * @return void
    */
    public function pre_update($properties = null)
    {
        parent::pre_update($properties);
        $this->set_last_modified('now');
    }
}

<?php

namespace Charcoal\Object;

use \Exception as Exception;
use \InvalidArgumentException as InvalidArgumentException;

/**
* Full implementation, as a trait, of the `HierarchicalInterface`
*/
trait HierarchicalTrait
{
    /**
     * The master, if any, in the hierarchy.
     *
     * @var HierarchicalInterface $master
     */
    private $master = null;

    /**
     * In-memory copy of the object's hierarchy.
     *
     * @var array $hierarchy
     */
    private $hierarchy = null;

    /**
     * In-memory copy of the object's children
     *
     * @var array $children
     */
    private $children;

    /**
     * In-memory copy of the object's siblings
     *
     * @var array $siblings
     */
    private $siblings;

    /**
     * @param mixed $master
     * @return HierarchicalInterface Chainable
     */
    public function set_master($master)
    {
        $this->master = $this->obj_from_ident($master);
        return $this;
    }

    /**
     * Get the immediate parent (master) of this object.
     * @return HierarchicalInterface|null
     */
    public function master()
    {
        return $this->master;
    }

    /**
     * Get wether this object has a parent (master) or not.
     * @return boolean
     */
    public function has_master()
    {
        $master = $this->master();
        return ($master !== null);
    }

    /**
     * Get wether this object is toplevel or not.
     * Top-level objects do not have a parent (master)
     * @return boolean
     */
    public function is_top_level()
    {
        $master = $this->master();
        return ($master === null);
    }

    /**
     * Get wether this object is on the last-level or not.
     * Last level objects do not have childen
     * @return boolean
     */
    public function is_last_level()
    {
        return !$this->has_children();
    }

    /**
     * Get the object's level in hierarchy.
     * Starts at "1" (top-level)
     * @return integer
     */
    public function hierarchy_level()
    {
        $hierarchy = $this->hierarchy();
        $level = (count($hierarchy) + 1);

        return $level;
    }

    /**
     * Get the top-level parent (master) of this object
     * @return HierarchicalInterface|null
     */
    public function toplevel_master()
    {
        $hierarchy = $this->inverted_hierarchy();
        if (isset($hierarchy[0])) {
            return $hierarchy[0];
        } else {
            return null;
        }
    }

    /**
     * Get all of this object's parents, from immediate to top-level.
     * @return array
     */
    public function hierarchy()
    {
        // Get from memory, if it was already set.
        /*if ($this->hierarchy !== null) {
            return $this->hierarchy;
        }*/

        $hierarchy = [];
        $master = $this->master();
        while ($master) {
            $hierarchy[] = $master;
            $master = $master->master();
        }

        $this->hierarchy = $hierarchy;
        return $this->hierarchy;
    }

    /**
     * Get all of this object's parents, inverted from top-level to immediate.
     * @return array
     */
    public function inverted_hierarchy()
    {
        $hierarchy = $this->hierarchy();
        return array_reverse($hierarchy);
    }

    /**
     * Get wether the object has a certain child directly underneath.
     * @param mixed $child The child object (or ident) to check against.
     * @return boolean
     */
    public function is_master_of($child)
    {
        $child = $this->obj_from_ident($child);
        return ($child->master() == $this);
    }

    /**
     * Get wether the object has a certain child, in its entire hierarchy
     * @param mixed $child The child object (or ident) to check against.
     * @return boolean
     */
    public function recursive_is_master_of($child)
    {
        $child = $this->obj_from_ident($child);
        // TODO
        return false;
    }

    /**
     * Get wether the object has any children at all
     * @return boolean
     */
    public function has_children()
    {
        $num_children = $this->num_children();
        return ($num_children > 0);
    }

    /**
     * Get the number of children directly under this object.
     * @return integer
     */
    public function num_children()
    {
        $children = $this->children();
        return count($children);
    }

    /**
     * Get the total number of children in the entire hierarchy.
     * This method counts all children and sub-children, unlike `num_children()` which only count 1 level.
     * @return integer
     */
    public function recursive_num_children()
    {
        // TODO
        return 0;
    }

    /**
     * @param array $children The children to set.
     * @return HierarchicalInterface Chainable
     */
    public function set_children(array $children)
    {
        $this->children = [];
        foreach ($children as $c) {
            $this->add_child($c);
        }
        return $this;
    }

    /**
     * @param mixed $child The child object (or ident) to add.
     * @return HierarchicalInterface Chainable
     */
    public function add_child($child)
    {
        $this->obj_from_ident($child);
        $this->children[] = $child;
        return $this;
    }


    /**
     * Get the children directly under this object.
     * @return array
     */
    public function children()
    {
        if ($this->children !== null) {
            return $this->children;
        }

        $this->children = $this->load_children();
        return $this->children;
    }

    /**
     * @return array
     */
    abstract public function load_children();

    /**
     * @param mixed $master The master object (or ident) to check against.
     * @return boolean
     */
    public function is_child_of($master)
    {
        $master = $this->obj_from_ident($master);
        if ($master === null) {
            return false;
        }
        return ($master == $this->master());
    }

    /**
     * @param mixed $master The master object (or ident) to check against.
     * @return boolean
     */
    public function recursive_is_child_of($master)
    {
        $master = $this->obj_from_ident($master);
        if ($master === null) {
            return false;
        }
        // TODO
    }

    /**
     * @return boolean
     */
    public function has_siblings()
    {
        $num_siblings = $this->num_siblings();
        return ($num_siblings > 1);
    }

    /**
     * @return integer
     */
    public function num_siblings()
    {
        $siblings = $this->siblings();
        return count($siblings);
    }

    /**
     * Get all the objects on the same level as this one.
     * @return array
     */
    public function siblings()
    {
        if ($this->siblings !== null) {
            return $this->siblings;
        }
        $master = $this->master();
        if ($master === null) {
            // Todo: return all top-level objects.
            $siblings = [];
        } else {
            // Todo: Remove "current" object from siblings
            $siblings = $master->children();
        }
        $this->siblings = $siblings;
        return $this->siblings;
    }

    /**
     * @param mixed $sibling
     * @return boolean
     */
    public function is_sibling_of($sibling)
    {
        $sibling = $this->obj_from_ident($sibling);
        return ($sibling->master() == $this->master());
    }

    /**
     * @param mixed $ident The ident.
     * @throws Exception If the object is not loadable.
     * @throws InvalidArgumentException If the ident is not a scalar value.
     * @return HierarchicalInterface|null
     */
    private function obj_from_ident($ident)
    {
        if ($ident === null) {
            return null;
        }
        $class = get_called_class();
        if (is_object($ident) && ($ident instanceof $class)) {
            return $ident;
        }

        if (!is_scalar($ident)) {
            throw new InvalidArgumentException(
                sprintf('Can not load object (not a scalar or a "%s")', $class)
            );
        }

        //$obj = ModelFactory::instance()->get($class);
        $obj = new $class;
        if (!is_callable([$obj, 'load'])) {
            throw new Exception('Can not load object. No loadable interface defined.');
        }
        $obj->load($ident);

        if ($obj->id()) {
            return $obj;
        } else {
            return null;
        }
    }
}

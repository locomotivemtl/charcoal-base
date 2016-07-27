<?php

namespace Charcoal\Object;

use \Exception;
use \InvalidArgumentException;

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
     * @var array|null $hierarchy
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
     * @param mixed $master The object's parent (or master).
     * @return HierarchicalInterface Chainable
     */
    public function setMaster($master)
    {
        $this->master = $this->objFromIdent($master);
        // Rebuild hierarchy
        $this->hierarchy = null;
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
    public function hasMaster()
    {
        $master = $this->master();
        return ($master !== null);
    }

    /**
     * Get wether this object is toplevel or not.
     * Top-level objects do not have a parent (master)
     * @return boolean
     */
    public function isTopLevel()
    {
        $master = $this->master();
        return ($master === null);
    }

    /**
     * Get wether this object is on the last-level or not.
     * Last level objects do not have childen
     * @return boolean
     */
    public function isLastLevel()
    {
        return !$this->hasChildren();
    }

    /**
     * Get the object's level in hierarchy.
     * Starts at "1" (top-level)
     * @return integer
     */
    public function hierarchyLevel()
    {
        $hierarchy = $this->hierarchy();
        $level = (count($hierarchy) + 1);

        return $level;
    }

    /**
     * Get the top-level parent (master) of this object
     * @return HierarchicalInterface|null
     */
    public function toplevelMaster()
    {
        $hierarchy = $this->invertedHierarchy();
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
        if (!isset($this->hierarchy)) {
            $hierarchy = [];
            $master = $this->master();
            while ($master) {
                $hierarchy[] = $master;
                $master = $master->master();
            }

            $this->hierarchy = $hierarchy;
        }

        return $this->hierarchy;
    }

    /**
     * Get all of this object's parents, inverted from top-level to immediate.
     * @return array
     */
    public function invertedHierarchy()
    {
        $hierarchy = $this->hierarchy();
        return array_reverse($hierarchy);
    }

    /**
     * Get wether the object has a certain child directly underneath.
     * @param mixed $child The child object (or ident) to check against.
     * @return boolean
     */
    public function isMasterOf($child)
    {
        $child = $this->objFromIdent($child);
        return ($child->master() == $this);
    }

    /**
     * Get wether the object has a certain child, in its entire hierarchy
     * @param mixed $child The child object (or ident) to check against.
     * @return boolean
     */
    public function recursiveIsMasterOf($child)
    {
        $child = $this->objFromIdent($child);
        // TODO
        return false;
    }

    /**
     * Get wether the object has any children at all
     * @return boolean
     */
    public function hasChildren()
    {
        $numChildren = $this->numChildren();
        return ($numChildren > 0);
    }

    /**
     * Get the number of children directly under this object.
     * @return integer
     */
    public function numChildren()
    {
        $children = $this->children();
        return count($children);
    }

    /**
     * Get the total number of children in the entire hierarchy.
     * This method counts all children and sub-children, unlike `numChildren()` which only count 1 level.
     * @return integer
     */
    public function recursiveNumChildren()
    {
        // TODO
        return 0;
    }

    /**
     * @param array $children The children to set.
     * @return HierarchicalInterface Chainable
     */
    public function setChildren(array $children)
    {
        $this->children = [];
        foreach ($children as $c) {
            $this->addChild($c);
        }
        return $this;
    }

    /**
     * @param mixed $child The child object (or ident) to add.
     * @return HierarchicalInterface Chainable
     */
    public function addChild($child)
    {
        $this->objFromIdent($child);
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

        $this->children = $this->loadChildren();
        return $this->children;
    }

    /**
     * @return array
     */
    abstract public function loadChildren();

    /**
     * @param mixed $master The master object (or ident) to check against.
     * @return boolean
     */
    public function isChildOf($master)
    {
        $master = $this->objFromIdent($master);
        if ($master === null) {
            return false;
        }
        return ($master == $this->master());
    }

    /**
     * @param mixed $master The master object (or ident) to check against.
     * @return boolean
     */
    public function recursiveIsChildOf($master)
    {
        $master = $this->objFromIdent($master);
        if ($master === null) {
            return false;
        }
        // TODO
    }

    /**
     * @return boolean
     */
    public function hasSiblings()
    {
        $numSiblings = $this->numSiblings();
        return ($numSiblings > 1);
    }

    /**
     * @return integer
     */
    public function numSiblings()
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
     * @param mixed $sibling The sibling to check.
     * @return boolean
     */
    public function isSiblingOf($sibling)
    {
        $sibling = $this->objFromIdent($sibling);
        return ($sibling->master() == $this->master());
    }

    /**
     * @param mixed $ident The ident.
     * @throws Exception If the object is not loadable.
     * @throws InvalidArgumentException If the ident is not a scalar value.
     * @return HierarchicalInterface|null
     */
    private function objFromIdent($ident)
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

        try {
            $obj = $this->modelFactory()->create($class);

            if (!is_callable([$obj, 'load'])) {
                throw new Exception(
                    'Can not load object. No loadable interface defined.'
                );
            }

            $obj->load($ident);

            if ($obj->id()) {
                return $obj;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Hierarchical objects must provide a model factory to create new (children) objects.
     * @return \Charcoal\Factory\FactoryInterface
     */
    abstract protected function modelFactory();
}

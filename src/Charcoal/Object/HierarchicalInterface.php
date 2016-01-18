<?php

namespace Charcoal\Object;

/**
 *
 */
interface HierarchicalInterface
{
    /**
     * Get wether this object has a parent (master) or not.
     * @return boolean
     */
    public function hasMaster();

    /**
     * Get wether this object is toplevel or not.
     * Top-level objects do not have a parent (master)
     * @return boolean
     */
    public function isTopLevel();

    /**
     * Get wether this object is on the last-level or not.
     * Last level objects do not have childen
     * @return boolean
     */
    public function isLastLevel();

    /**
     * Get the object's level in hierarchy.
     * Starts at "1" (top-level)
     * @return integer
     */
    public function hierarchyLevel();

    /**
     * Get the immediate parent (master) of this object.
     * @return HierarchicalInterface
     */
    public function master();

    /**
     * Get the top-level parent (master) of this object
     * @return HierarchicalInterface
     */
    public function toplevelMaster();

    /**
     * Get all of this object's parents, from immediate to top-level.
     * @return array
     */
    public function hierarchy();

    /**
     * Get all of this object's parents, inverted from top-level to immediate.
     * @return array
     */
    public function invertedHierarchy();

    /**
     * Get wether the object has a certain child directly underneath.
     * @param mixed $child The child object (or ident) to check against.
     * @return boolean
     */
    public function isMasterOf($child);

    /**
     * Get wether the object has a certain child, in its entire hierarchy
     * @param mixed $child The child object (or ident) to check against.
     * @return boolean
     */
    public function recursiveIsMasterOf($child);

    /**
     * Get wether the object has any children at all
     * @return boolean
     */
    public function hasChildren();

    /**
     * Get the number of chidlren directly under this object.
     * @return integer
     */
    public function numChildren();

    /**
     * Get the total number of children in the entire hierarchy.
     * This method counts all children and sub-children, unlike `numChildren()` which only count 1 level.
     * @return integer
     */
    public function recursiveNumChildren();


    /**
     * Get the children directly under this object.
     * @return array
     */
    public function children();

    /**
     * @param mixed $master The master object (or ident) to check against.
     * @return boolean The master object (or ident) to check against.
     */
    public function isChildOf($master);

    /**
     * @param mixed $master The master object (or ident) to check against.
     * @return boolean
     */
    public function recursiveIsChildOf($master);

    /**
     * @return boolean
     */
    public function hasSiblings();

    /**
     * @return integer
     */
    public function numSiblings();

    /**
     * Get all the objects on the same level as this one.
     * @return array
     */
    public function siblings();

    /**
     * @param mixed $sibling The sibling object (or ident) to check against.
     * @return boolean
     */
    public function isSiblingOf($sibling);
}

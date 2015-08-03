<?php

namespace Charcoal\Object;

/**
*
*/
interface HierarchicalInterface
{
    /**
    * @param array $data
    * @return HierarchicalInterface Chainable
    */
    public function set_hierarchical_data(array $data);

    /**
    * Get wether this object has a parent (master) or not.
    * @return boolean
    */
    public function has_master();

    /**
    * Get wether this object is toplevel or not.
    * Top-level objects do not have a parent (master)
    * @return boolean
    */
    public function is_top_level();

    /**
    * Get wether this object is on the last-level or not.
    * Last level objects do not have childen
    * @return boolean
    */
    public function is_last_level();

    /**
    * Get the object's level in hierarchy.
    * Starts at "1" (top-level)
    * @return integer
    */
    public function hierarchy_level();

    /**
    * Get the immediate parent (master) of this object.
    * @return HierarchicalInterface
    */
    public function master();

    /**
    * Get the top-level parent (master) of this object
    * @return HierarchicalInterface
    */
    public function toplevel_master();

    /**
    * Get all of this object's parents, from immediate to top-level.
    * @return array
    */
    public function hierarchy();

    /**
    * Get all of this object's parents, inverted from top-level to immediate.
    * @return array
    */
    public function inverted_hierarchy();

    /**
    * Get wether the object has a certain child directly underneath.
    * @param mixed $child
    * @return boolean
    */
    public function is_master_of($child);

    /**
    * Get wether the object has a certain child, in its entire hierarchy
    * @param mixed $child
    * @return boolean
    */
    public function recursive_is_master_of($child);

    /**
    * Get wether the object has any children at all
    * @return boolean
    */
    public function has_children();

    /**
    * Get the number of chidlren directly under this object.
    * @return integer
    */
    public function num_children();

    /**
    * Get the total number of children in the entire hierarchy.
    * This method counts all children and sub-children, unlike `num_children()` which only count 1 level.
    * @return integer
    */
    public function recursive_num_children();


    /**
    * Get the children directly under this object.
    * @return array
    */
    public function children();

    /**
    * @param mixed $master
    * @return boolean
    */
    public function is_child_of($master);
    /**
    * @param mixed $master
    * @return boolean
    */
    public function recursive_is_child_of($master);

    /**
    * @return boolean
    */
    public function has_siblings();
    /**
    * @return integer
    */
    public function num_siblings();

    /**
    * Get all the objects on the same level as this one.
    * @return array
    */
    public function siblings();

    /**
    * @param mixed $sibling
    * @return boolean
    */
    public function is_sibling_of($sibling);
}

<?php

namespace Charcoal\Object;

/**
 *
 */
interface ObjectRevisionInterface
{
    /**
     * @param string $obj_type The object type (type-ident).
     * @throws InvalidArgumentException If the obj type parameter is not a string.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function set_obj_type($obj_type);

    /**
     * @return string
     */
    public function obj_type();

    /**
     * @param mixed $obj_id The object ID.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function set_obj_id($obj_id);

    /**
     * @return mixed
     */
    public function obj_id();

    /**
     * @param integer $rev_num The revision number.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function set_rev_num($rev_num);

    /**
     * @return integer
     */
    public function rev_num();

    /**
     * @param mixed $rev_ts The revision's timestamp.
     * @throws InvalidArgumentException If the timestamp is invalid.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function set_rev_ts($rev_ts);

    /**
     * @return DateTime|null
     */
    public function rev_ts();

    /**
     * @param string $rev_user The revision user ident.
     * @throws InvalidArgumentException If the revision user parameter is not a string.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function set_rev_user($rev_user);

    /**
     * @return string
     */
    public function rev_user();

    /**
     * @param array $data The previous revision data.
     * @return ObjectRevision
     */
    public function set_data_prev(array $data);

    /**
     * @return array
     */
    public function data_prev();

    /**
     * @param array $data The current revision (object) data.
     * @return ObjectRevision
     */
    public function set_data_obj(array $data);

    /**
     * @return array
     */
    public function data_obj();

     /**
      * @param array $data The data diff.
      * @return ObjectRevision
      */
    public function set_data_diff(array $data);

    /**
     * @return array
     */
    public function data_diff();

    /**
     * Create a new revision from an object
     *
     * 1. Load the last revision
     * 2. Load the current item from DB
     * 3. Create diff from (1) and (2).
     *
     * @param string $obj_type The object type to create the revision from.
     * @param mixed  $obj_id   The object ID to create the revision from.
     * @return ObjectRevisionInterface Chainable
     */
    public function create_from_object($obj_type, $obj_id);

    /**
     * @param array $data_prev Optional. The previous revision data.
     * @param array $data_obj  Optional. The current revision (object) data.
     * @return array The diff data
     */
    public function create_diff(array $data_prev, array $data_obj);

    /**
     * Recursive array_diff.
     *
     * @param array $array1 First array.
     * @param array $array2 Second array.
     * @return array The array diff.
     */
    public function recursive_diff(array $array1, array $array2);

    /**
     * @param string $obj_type The object type to load the last revision of.
     * @param mixed  $obj_id   The object ID to load the last revision of.
     * @return ObjectRevision The last revision for the give object.
     */
    public function last_object_revision($obj_type, $obj_id);
}

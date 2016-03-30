<?php

namespace Charcoal\Object;

/**
 *
 */
interface ObjectRevisionInterface
{
    /**
     * @param string $objType The object type (type-ident).
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setObjType($objType);

    /**
     * @return string
     */
    public function objType();

    /**
     * @param mixed $objId The object ID.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setObjId($objId);

    /**
     * @return mixed
     */
    public function objId();

    /**
     * @param integer $revNum The revision number.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setRevNum($revNum);

    /**
     * @return integer
     */
    public function revNum();

    /**
     * @param mixed $revTs The revision's timestamp.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setRevTs($revTs);

    /**
     * @return DateTime|null
     */
    public function revTs();

    /**
     * @param string $revUser The revision user ident.
     * @return \Charcoal\Object\ObjectRevisionInterface Chainable
     */
    public function setRevUser($revUser);

    /**
     * @return string
     */
    public function revUser();

    /**
     * @param array $data The previous revision data.
     * @return ObjectRevision
     */
    public function setDataPrev(array $data);

    /**
     * @return array
     */
    public function dataPrev();

    /**
     * @param array $data The current revision (object) data.
     * @return ObjectRevision
     */
    public function setDataObj(array $data);

    /**
     * @return array
     */
    public function dataObj();

     /**
      * @param array $data The data diff.
      * @return ObjectRevision
      */
    public function setDataDiff(array $data);

    /**
     * @return array
     */
    public function dataDiff();

    /**
     * Create a new revision from an object
     *
     * 1. Load the last revision
     * 2. Load the current item from DB
     * 3. Create diff from (1) and (2).
     *
     * @param string $objType The object type to create the revision from.
     * @param mixed  $objId   The object ID to create the revision from.
     * @return ObjectRevisionInterface Chainable
     */
    public function createFromObject($objType, $objId);

    /**
     * @param array $dataPrev Optional. The previous revision data.
     * @param array $dataObj  Optional. The current revision (object) data.
     * @return array The diff data
     */
    public function createDiff(array $dataPrev, array $dataObj);

    /**
     * Recursive arrayDiff.
     *
     * @param array $array1 First array.
     * @param array $array2 Second array.
     * @return array The array diff.
     */
    public function recursiveDiff(array $array1, array $array2);

    /**
     * @param string $objType The object type to load the last revision of.
     * @param mixed  $objId   The object ID to load the last revision of.
     * @return ObjectRevision The last revision for the give object.
     */
    public function lastObjectRevision($objType, $objId);
}

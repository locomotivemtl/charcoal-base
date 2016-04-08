<?php

namespace Charcoal\Object;

// Dependencies from `PHP`
use \Exception;
use \InvalidArgumentException;
use \DateTime;
use \DateTimeInterface;

// From `charcoal-core`
use \Charcoal\Model\AbstractModel;
use \Charcoal\Core\IndexableInterface;
use \Charcoal\Core\IndexableTrait;

// Local namespace dependencies
use \Charcoal\Object\RevisionableInterface;

/**
 *
 */
class ObjectRevision extends AbstractModel implements
    IndexableInterface
{
    use IndexableTrait;

    /**
     * Object type of this revision (required)
     * @var string $objType
     */
    private $objType;

    /**
     * Object ID of this revision (required)
     * @var mixed $objectId
     */
    private $objId;

    /**
     * Revision number. Sequential integer for each object's ID. (required)
     * @var integer $revNum
     */
    private $revNum;

    /**
     * Timestamp; when this revision was created
     * @var string $revTs (DateTime)
     */
    private $revTs;

    /**
     * The (admin) user that was
     * @var string $revUser
     */
    private $revUser;

    /**
     * @var array $dataPrev
     */
    private $dataPrev;

    /**
     * @var array $dataObj
     */
    private $dataObj;

    /**
     * @var array $dataDiff
     */
    private $dataDiff;

    /**
     * @param string $objType The object type (type-ident).
     * @throws InvalidArgumentException If the obj type parameter is not a string.
     * @return ObjectRevision Chainable
     */
    public function setObjType($objType)
    {
        if (!is_string($objType)) {
            throw new InvalidArgumentException(
                'Revisions obj type must be a string.'
            );
        }
        $this->objType = $objType;
        return $this;
    }

    /**
     * @return string
     */
    public function objType()
    {
        return $this->objType;
    }

    /**
     * @param mixed $objId The object ID.
     * @return ObjectRevision Chainable
     */
    public function setObjId($objId)
    {
        $this->objId = $objId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function objId()
    {
        return $this->objId;
    }

    /**
     * @param integer $revNum The revision number.
     * @throws InvalidArgumentException If the revision number argument is not numerical.
     * @return ObjectRevision Chainable
     */
    public function setRevNum($revNum)
    {
        if (!is_numeric($revNum)) {
            throw new InvalidArgumentException(
                'Revision number must be an integer (numeric).'
            );
        }
        $this->revNum = (int)$revNum;
        return $this;
    }

    /**
     * @return integer
     */
    public function revNum()
    {
        return $this->revNum;
    }

    /**
     * @param mixed $revTs The revision's timestamp.
     * @throws InvalidArgumentException If the timestamp is invalid.
     * @return ObjectRevision Chainable
     */
    public function setRevTs($revTs)
    {
        if ($revTs === null) {
            $this->revTs = null;
            return $this;
        }
        if (is_string($revTs)) {
            $revTs = new DateTime($revTs);
        }
        if (!($revTs instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Revision Date" value. Must be a date/time string or a DateTimeInterface object.'
            );
        }
        $this->revTs = $revTs;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function revTs()
    {
        return $this->revTs;
    }

    /**
     * @param string $revUser The revision user ident.
     * @throws InvalidArgumentException If the revision user parameter is not a string.
     * @return ObjectRevision Chainable
     */
    public function setRevUser($revUser)
    {
        if ($revUser === null) {
            $this->revUser = null;
            return $this;
        }
        if (!is_string($revUser)) {
            throw new InvalidArgumentException(
                'Revision user must be a string.'
            );
        }
        $this->revUser = $revUser;
        return $this;
    }

    /**
     * @return string
     */
    public function revUser()
    {
        return $this->revUser;
    }

    /**
     * @param string|array $data The previous revision data.
     * @return ObjectRevision
     */
    public function setDataPrev($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        if ($data === null) {
            $data = [];
        }
        $this->dataPrev = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function dataPrev()
    {
        return $this->dataPrev;
    }

    /**
     * @param array|string $data The current revision (object) data.
     * @return ObjectRevision
     */
    public function setDataObj($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        if ($data === null) {
            $data = [];
        }
        $this->dataObj = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function dataObj()
    {
        return $this->dataObj;
    }

     /**
      * @param array|string $data The data diff.
      * @return ObjectRevision
      */
    public function setDataDiff($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        if ($data === null) {
            $data = [];
        }
        $this->dataDiff = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function dataDiff()
    {
        return $this->dataDiff;
    }

    /**
     * Create a new revision from an object
     *
     * 1. Load the last revision
     * 2. Load the current item from DB
     * 3. Create diff from (1) and (2).
     *
     * @param RevisionableInterface $obj The object to create the revision from.
     * @return ObjectRevision Chainable
     */
    public function createFromObject(RevisionableInterface $obj)
    {
        $prevRev = $this->lastObjectRevision($obj);

        $this->setObjType($obj->objType());
        $this->setObjId($obj->id());
        $this->setRevNum($prevRev->revNum() + 1);
        $this->setRevTs('now');

        $this->setDataObj($obj->data([
            'sortable'=>false
        ]));
        $this->setDataPrev($prevRev->dataObj());

        $diff = $this->createDiff();
        $this->setDataDiff($diff);

        return $this;
    }

    /**
     * @param array $dataPrev Optional. Previous revision data.
     * @param array $dataObj  Optional. Current revision (object) data.
     * @return array The diff data
     */
    public function createDiff(array $dataPrev = null, array $dataObj = null)
    {
        if ($dataPrev === null) {
            $dataPrev = $this->dataPrev();
        }
        if ($dataObj === null) {
            $dataObj = $this->dataObj();
        }
        $dataDiff = $this->recursiveDiff($dataPrev, $dataObj);
        return $dataDiff;
    }

    /**
     * Recursive arrayDiff.
     *
     * @param array $array1 First array.
     * @param array $array2 Second Array.
     * @return array The array diff.
     */
    public function recursiveDiff(array $array1, array $array2)
    {
        $diff = [];

        // Compare array1
        foreach ($array1 as $key => $value) {
            if (!array_key_exists($key, $array2)) {
                $diff[0][$key] = $value;
            } elseif (is_array($value)) {
                if (!is_array($array2[$key])) {
                    $diff[0][$key] = $value;
                    $diff[1][$key] = $array2[$key];
                } else {
                    $new = $this->recursiveDiff($value, $array2[$key]);
                    if ($new !== false) {
                        if (isset($new[0])) {
                            $diff[0][$key] = $new[0];
                        }
                        if (isset($new[1])) {
                            $diff[1][$key] = $new[1];
                        }
                    }
                }
            } elseif ($array2[$key] !== $value) {
                $diff[0][$key] = $value;
                $diff[1][$key] = $array2[$key];
            }
        }

        // Compare array2
        foreach ($array2 as $key => $value) {
            if (!array_key_exists($key, $array1)) {
                $diff[1][$key] = $value;
            }
        }

        return $diff;
    }

    /**
     * @param RevisionableInterface $obj The object  to load the last revision of.
     * @return ObjectRevision The last revision for the give object.
     */
    public function lastObjectRevision(RevisionableInterface $obj)
    {
        if ($this->source()->tableExists() === false) {
            /** @todo Optionnally turn off for some models */
            $this->source()->createTable();
        }

        $classname = get_class($this);
        $rev = new $classname([
            'logger' => $this->logger
        ]);
        $rev->loadFromQuery(
            '
            SELECT
                *
            FROM
                `'.$this->source()->table().'`
            WHERE
                `obj_type` = :obj_type
            AND
                `obj_id` = :obj_id
            ORDER BY
                `rev_ts` desc
            LIMIT 1',
            [
                'obj_type' => $obj->objType(),
                'obj_id'   => $obj->id()
            ]
        );

        return $rev;
    }
}

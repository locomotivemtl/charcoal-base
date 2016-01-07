<?php

namespace Charcoal\Object;

// Dependencies from `PHP`
use \Exception;
use \InvalidArgumentException;
use \Datetime;
use \DateTimeInterface;

// From `charcoal-core`
use \Charcoal\Model\AbstractModel;
use \Charcoal\Model\ModelFactory;
use \Charcoal\Core\IndexableInterface;
use \Charcoal\Core\IndexableTrait;

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
     * @var string $obj_type
     */
    private $obj_type;

    /**
     * Object ID of this revision (required)
     * @var mixed $object_id
     */
    private $obj_id;

    /**
     * Revision number. Sequential integer for each object's ID. (required)
     * @var integer $rev_num
     */
    private $rev_num;

    /**
     * Timestamp; when this revision was created
     * @var string $rev_ts (Datetime)
     */
    private $rev_ts;

    /**
     * The (admin) user that was
     * @var string $rev_user
     */
    private $rev_user;

    /**
     * @var array $data_prev
     */
    private $data_prev;

    /**
     * @var array $data_obj
     */
    private $data_obj;

    /**
     * @var array $data_diff
     */
    private $data_diff;

    /**
     * @param string $obj_type The object type (type-ident).
     * @throws InvalidArgumentException If the obj type parameter is not a string.
     * @return ObjectRevision Chainable
     */
    public function set_obj_type($obj_type)
    {
        if (!is_string($obj_type)) {
            throw new InvalidArgumentException(
                'Revisions obj type must be a string.'
            );
        }
        $this->obj_type = $obj_type;
        return $this;
    }

    /**
     * @return string
     */
    public function obj_type()
    {
        return $this->obj_type;
    }

    /**
     * @param mixed $obj_id The object ID.
     * @return ObjectRevision Chainable
     */
    public function set_obj_id($obj_id)
    {
        $this->obj_id = $obj_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function obj_id()
    {
        return $this->obj_id;
    }

    /**
     * @param integer $rev_num The revision number.
     * @throws InvalidArgumentException If the revision number argument is not numerical.
     * @return ObjectRevision Chainable
     */
    public function set_rev_num($rev_num)
    {
        if (!is_numeric($rev_num)) {
            throw new InvalidArgumentException(
                'Revision number must be an integer (numeric).'
            );
        }
        $this->rev_num = (int)$rev_num;
        return $this;
    }

    /**
     * @return integer
     */
    public function rev_num()
    {
        return $this->rev_num;
    }

    /**
     * @param mixed $rev_ts The revision's timestamp.
     * @throws InvalidArgumentException If the timestamp is invalid.
     * @return ObjectRevision Chainable
     */
    public function set_rev_ts($rev_ts)
    {
        if ($rev_ts === null) {
            return $this;
        }
        if (is_string($rev_ts)) {
            try {
                $rev_ts = new DateTime($rev_ts);
            } catch (Exception $e) {
                throw new InvalidArgumentException($e->getMessage());
            }
        }
        if (!($rev_ts instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Revision Date" value. Must be a date/time string or a DateTimeInterface object.'
            );
        }
        $this->rev_ts = $rev_ts;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function rev_ts()
    {
        return $this->rev_ts;
    }

    /**
     * @param string $rev_user The revision user ident.
     * @throws InvalidArgumentException If the revision user parameter is not a string.
     * @return ObjectRevision Chainable
     */
    public function set_rev_user($rev_user)
    {
        if($rev_user === null) {
            $this->rev_user = null;
            return $this;
        }
        if (!is_string($rev_user)) {
            throw new InvalidArgumentException(
                'Revision user must be a string.'
            );
        }
        $this->rev_user = $rev_user;
        return $this;
    }

    /**
     * @return string
     */
    public function rev_user()
    {
        return $this->rev_user;
    }

    /**
     * @param string|array $data The previous revision data.
     * @return ObjectRevision
     */
    public function set_data_prev($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        if ($data === null) {
            $data = [];
        }
        $this->data_prev = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function data_prev()
    {
        return $this->data_prev;
    }

    /**
     * @param array|string $data The current revision (object) data.
     * @return ObjectRevision
     */
    public function set_data_obj($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        if ($data === null) {
            $data = [];
        }
        $this->data_obj = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function data_obj()
    {
        return $this->data_obj;
    }

     /**
      * @param array|string $data The data diff.
      * @return ObjectRevision
      */
    public function set_data_diff($data)
    {
        if(!is_array($data)) {
            $data = json_decode($data, true);
        }
        if ($data === null) {
            $data = [];
        }
        $this->data_diff = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function data_diff()
    {
        return $this->data_diff;
    }

    /**
     * Create a new revision from an object
     *
     * 1. Load the last revision
     * 2. Load the current item from DB
     * 3. Create diff from (1) and (2).
     *
     * @param string $obj_type The object type to create the revision from.
     * @param mixed  $obj_id   The object ID to create the revision from.
     * @return ObjectRevision Chainable
     */
    public function create_from_object($obj_type, $obj_id)
    {
        $prev_rev = $this->last_object_revision($obj_type, $obj_id);

        $model_factory = new ModelFactory();
        $obj = $model_factory->create($obj_type, [
            'logger' => $this->logger
        ]);
        $obj->load($obj_id);

        $this->set_obj_type($obj_type);
        $this->set_obj_id($obj_id);
        $this->set_rev_num($prev_rev->rev_num() + 1);
        $this->set_rev_ts('now');

        $this->set_data_obj($obj->data());
        $this->set_data_prev($prev_rev->data_obj());

        $diff = $this->create_diff();
        $this->set_data_diff($diff);

        return $this;
    }

    /**
     * @param array $data_prev Optional. Previous revision data.
     * @param array $data_obj Optional. Current revision (object) data.
     * @return array The diff data
     */
    public function create_diff(array $data_prev=null, array $data_obj=null)
    {
        if ($data_prev === null) {
            $data_prev = $this->data_prev();
        }
        if ($data_obj === null) {
            $data_obj = $this->data_obj();
        }
        $data_diff = $this->recursive_diff($data_prev, $data_obj);
        return $data_diff;
    }

    /**
     * Recursive array_diff.
     *
     * @param array $array1 First array.
     * @param array $array2 Second Array.
     * @return array The array diff.
     */
    public function recursive_diff(array $array1, array $array2)
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
                    $new = $this->recursive_diff($value, $array2[$key]);
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
     * @param string $obj_type The object type to load the last revision of.
     * @param mixed  $obj_id   The object ID to load the last revision of.
     * @return ObjectRevision The last revision for the give object.
     */
    public function last_object_revision($obj_type, $obj_id)
    {
        $classname = get_class($this);
        $rev = new $classname([
            'logger' => $this->logger
        ]);
        $rev->load_from_query('
            select
                *
            from
                `'.$this->source()->table().'`
            where
                `obj_type` = :obj_type
            and
                `obj_id` = :obj_id
            order by
                `rev_ts` desc
            limit 1',
            [
                'obj_type' => $obj_type,
                'obj_id' => $obj_id
            ]
        );

        return $rev;
    }
}

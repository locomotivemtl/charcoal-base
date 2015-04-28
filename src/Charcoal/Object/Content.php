<?php

namespace Charcoal\Object;

use \DateTime as DateTime;
use \InvalidArgumentException as InvalidArgumentException;

use \Charcoal\Object\AbstractObject as AbstractObject;
use \Charcoal\Object\ContentInterface as ContentInterface;

class Content extends AbstractObject implements ContentInterface
{
    private $_created;
    private $_created_by;
    private $_last_modified;
    private $_last_modified_by;

    public function set_data($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Data must be an array');
        }

        //parent::set_data($data);
        if (isset($data['created']) && $data['created'] !== null) {
            $this->set_created($data['created']);
        }
        if (isset($data['created_by']) && $data['created_by'] !== null) {
            $this->set_created_by($data['created_by']);
        }
        if(isset($data['last_modified']) && $data['last_modified'] !== null) {
            $this->set_last_modified($data['last_modified']);
        }
        if(isset($data['last_modified_by']) && $data['last_modified_by'] !== null) {
            $this->set_last_modified_by($data['last_modified_by']);
        }

        return $this;
    }

    public function set_created($created)
    {
        if (is_string($created)) {
            $created = new DateTime($created);
        }
        if (!($created instanceof DateTime)) {
            throw new InvalidArgumentException('Created must be a Datetime object or a valid datetime string');
        }
        $this->_created = $created;
        return $this;
    }

    public function created()
    {
        return $this->_created;
    }

    public function set_created_by($created_by)
    {
        $this->_created_by = $created_by;
        return $this;
    }

    public function created_by()
    {
        return $this->_created_by;
    }

    public function set_last_modified($last_modified)
    {
        if (is_string($last_modified)) {
            $last_modified = new DateTime($last_modified);
        }
        if (!($last_modified instanceof DateTime)) {
            throw new InvalidArgumentException('Created must be a Datetime object or a valid datetime string');
        }
        $this->_last_modified = $last_modified;
        return $this;
    }

    public function last_modified()
    {
        return $this->_last_modified;
    }

    public function set_last_modified_by($last_modified_by)
    {
        $this->_last_modified_by = $last_modified_by;
        return $this;
    }

    public function last_modified_by()
    {
        return $this->_last_modified_by;
    }

    public function pre_save()
    {
        //parent::pre_save();

        $this->set_created('now');
        $this->set_last_modified('now');
    }
}

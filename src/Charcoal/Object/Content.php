<?php

namespace \Charcoal\Object;

use \Charcoal\Object\AbstractObject as AbstractObject;
use \Charcoal\Object\ContentInterface as ContentInterface;

class Content extends AbstractObject implements ContentInterface
{
    private $_created;
    private $_created_by;
    private $_last_modified;
    private $_last_modified_by;

    public function set_created($created)
    {
        $this->_created = new DateTime($created);
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
        $this->_last_modified = new DateTime($last_modified);
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

    public function last_revision()
    {
        // @todo
        return null;
    }

    public function pre_save()
    {
        parent::pre_save();

        $this->set_created('now');
        $this->set_last_modified('now');
    }
}

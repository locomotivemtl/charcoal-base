<?php

namespace Charcoal\Object;

interface ContentInterface
{
    /**
    * @param boolean $active
    * @return Content Chainable
    */
    public function set_active($active);
    /**
    * @return boolean
    */
    public function active();

    /**
    * @param integer $position
    * @return Content Chainable
    */
    public function set_position($position);
    /**
    * @return integer
    */
    public function position();

    /**
    * @param DateTime|string $created
    * @return Content Chainable
    */
    public function set_created($created);
    /**
    * @return DateTime|null
    */
    public function created();

    /**
    * @param mixed $created_by
    * @return Content Chainable
    */
    public function set_created_by($created_by);
    /**
    * @return mixed
    */
    public function created_by();

    /**
    * @param DateTime|string $last_modified
    * @return Content Chainable
    */
    public function set_last_modified($last_modified);
    /**
    * @return DateTime
    */
    public function last_modified();

    /**
    * @param mixed $last_modified_by
    * @return Content Chainable
    */
    public function set_last_modified_by($last_modified_by);
    /**
    * @return
    */
    public function last_modified_by();

    //public function last_revision();
    //public function revision($revision_num);
}

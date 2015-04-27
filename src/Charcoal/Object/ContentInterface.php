<?php

namespace Charcoal\Object;

interface ContentInterface
{
    public function set_created($created);
    public function created();

    public function set_created_by($created_by);
    public function created_by();

    public function set_last_modified($last_modified);
    public function last_modified();

    public function set_last_modified_by($last_modified_by);
    public function last_modified_by();

    public function last_revision();
    public function revision($revision_num);
}

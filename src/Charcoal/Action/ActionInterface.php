<?php

namespace Charcoal\Action;

interface ActionInterface
{
    public function set_mode($mode);
    public function mode();
    public function set_success($success);
    public function success();
}

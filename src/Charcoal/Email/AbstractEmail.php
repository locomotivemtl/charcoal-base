<?php

namespace Charcoal\Email;

use \Charcoal\Email\EmailInterface as EmailInterface;

abstract class AbstractEmail implements EmailInterface
{
    abstract public function send();
    abstract public function queue();
}

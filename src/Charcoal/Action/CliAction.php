<?php

namespace Charcoal\Action;

use \Charcoal\Charcoal as Charcoal;

abstract class CliAction extends AbstractAction implements CliActionInterface
{
    use CliActionTrait;
}

<?php

namespace Charcoal\Action;

use \Charcoal\Charcoal as Charcoal;

abstract class CliAction extends AbstractAction implements CliActionInterface
{
    use CliActionTrait;

    public function set_data($data)
    {
        //parent::set_data($data);
        $this->set_cli_data($data);

        return $this;
    }

}

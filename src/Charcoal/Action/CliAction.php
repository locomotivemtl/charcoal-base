<?php

namespace Charcoal\Action;

use \Charcoal\Charcoal as Charcoal;

abstract class CliAction extends AbstractAction implements CliActionInterface
{
    use CliActionTrait;
    

    /**
    * @param array $data
    * @return CliAction Chainable
    */
    public function set_data(array $data)
    {
        //parent::set_data($data);
        $this->set_cli_data($data);
        $this->set_cron_data($data);

        return $this;
    }

}

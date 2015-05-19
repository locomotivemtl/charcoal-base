<?php

namespace Charcoals\Tests\Action;

use \Charcoal\Action\CliAction as CliAction;

class CliActionClass extends CliAction
{
    public function response()
    {
        return [
            'success'=>$this->success()
        ];
    }
}

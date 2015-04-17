<?php

namespace Charcoal\Action;

use \Charcoal\Action\ActionInterface as ActionInterface;

/**
* Default implementation, as abstract class, of `ActionInterface`
*/
abstract class AbstractAction implements ActionInterface
{
    const MODE_JSON = 'json';
    const MODE_REDIRECT = 'redirect';
    const DEFAULT_MODE = self::MODE_REDIRECT;

    private $_mode = self::DEFAULT_MODE;
    private $_success = false;

    /**
    * @param string
    * @throws \InvalidArgumentException if mode is not a string
    * @return ActionInterface Chainable
    */
    public function set_mode($mode)
    {
        if(!is_string($mode)) {
            throw new \InvalidArgumentException('Mode needs to be a string');
        }
        $this->_mode = $mode;
        return $this;
    }

    /**
    * @return string
    */
    public function mode()
    {
        return $this->_mode;
    }

    /**
    * @param bool $success
    * @throws \InvalidArgumentException if success is not a boolean
    * @return ActionInterface Chainable
    */
    public function set_success($success)
    {
        if(!is_bool($success)) {
            throw new \InvalidArgumentException('Success needs to be a boolean');
        }
        $this->_success = $success;
        return $this;
    }

    /**
    * @return bool
    */
    public function success()
    {
        return $this->_success;
    }

    /**
    * @param int $http_code
    * @return mixed
    */
    abstract public function output($http_code=200);

    /**
    * @return string
    */
    public function success_url()
    {
        return $this->referer();
    }

    /**
    * @return string
    */
    public function failure_url()
    {
        return $this->referer();
    }

    /**
    * @return string
    */
    public function redirect_url()
    {
        if($this->success()) {
            return $this->success_url();
        }
        else {
            return $this->failure_url();
        }
    }

    /**
    * @return string
    */
    public function referer()
    {
        return Charcoal::app()->request->getReferrer();
    }
}

<?php

namespace Charcoal\Action;

use \InvalidArgumentException as InvalidArgumentException;

use \Charcoal\Charcoal as Charcoal;

use \Charcoal\Action\ActionInterface as ActionInterface;

/**
* Default implementation, as abstract class, of `ActionInterface`
*/
abstract class AbstractAction implements ActionInterface
{
    const MODE_JSON = 'json';
    const MODE_REDIRECT = 'redirect';
    const MODE_BOOLEAN = 'boolean';
    const MODE_OUTPUT = 'output';
    const DEFAULT_MODE = self::MODE_REDIRECT;

    /**
    * @var string $_mode
    */
    private $_mode = self::DEFAULT_MODE;
    /**
    * @var boolean $_success
    */
    private $_success = false;

    /**
    * @param string $mode
    * @throws InvalidArgumentException if mode is not a string
    * @return ActionInterface Chainable
    */
    public function set_mode($mode)
    {
        if (!is_string($mode)) {
            throw new InvalidArgumentException('Mode needs to be a string');
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
    * @throws InvalidArgumentException if success is not a boolean
    * @return ActionInterface Chainable
    */
    public function set_success($success)
    {
        if (!is_bool($success)) {
            throw new InvalidArgumentException('Success needs to be a boolean');
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
    * @return string
    */
    abstract public function response();

    /**
    * @param integer $http_code
    * @throws \Exception if mode is invalid
    * @return void
    */
    public function output($http_code = 200)
    {
        $response = $this->response();
        $mode = $this->mode();

        if ($mode == self::MODE_JSON) {
            try {
                Charcoal::app()->response->setStatus($http_code);
                Charcoal::app()->response->headers->set('Content-Type', 'application/json');
            } catch (\Exception $e) {
                http_response_code($http_code);
                if (!headers_sent()) {
                    header('Content-Type', 'application/json');
                }
            }
            echo json_encode($response);
        } elseif ($mode == self::MODE_REDIRECT) {
            Charcoal::app()->response->redirect($this->redirect_url(), $http_code);
        } else {
            throw new \Exception('Invalid mode');
        }
    }

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
        if ($this->success()) {
            return $this->success_url();
        } else {
            return $this->failure_url();
        }
    }

    /**
    * @return string
    */
    public function referer()
    {
        try {
            return Charcoal::app()->request->getReferrer();
        } catch (\Exception $e) {
            return '';
        }
    }
}

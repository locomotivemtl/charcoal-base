<?php

namespace Charcoal\Action;

use \Exception;
use \InvalidArgumentException;

use \Charcoal\Charcoal;

use \Charcoal\Action\ActionInterface;

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
    * @var string $_next_url;
    */
    private $_next_url;

    /**
    * @param string $mode
    * @throws InvalidArgumentException if mode is not a string
    * @return ActionInterface Chainable
    */
    public function set_mode($mode)
    {
        if (!is_string($mode)) {
            throw new InvalidArgumentException(
                'Mode needs to be a string'
            );
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
            throw new InvalidArgumentException(
                'Success needs to be a boolean'
            );
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
    * @param string $url
    * @throws InvalidArgumentException if success is not a boolean
    * @return ActionInterface Chainable
    */
    public function set_next_url($url)
    {
        if (!is_string($url)) {
            throw new InvalidArgumentException(
                'URL needs to be a string'
            );
        }
        $this->_next_url = $url;
        return $this;
    }

    /**
    * @return bool
    */
    public function next_url()
    {
        return $this->_next_url;
    }

    /**
    * @return string
    */
    abstract public function response();

    /**
    * @param integer $http_code
    * @throws Exception if mode is invalid
    * @return void
    */
    public function output($response)
    {
        $res = $this->response();
        $mode = $this->mode();

        if ($mode == self::MODE_JSON) {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode($response));
        } elseif ($mode == self::MODE_REDIRECT) {
            return $response
                ->withHeader('Location', $this->redirect_url());
        } else {
            throw new Exception(
                sprintf('Invalid mode "%s"', $mode)
            );
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
            $c = Charcoal::app()->getContainer();
            return $c->get('environment')->get('HTTP_REFERER');
        } catch (Exception $e) {
            return '';
        }
    }
}

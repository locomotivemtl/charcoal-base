<?php

namespace Charcoal\Action;

// Local namespace dependencies
use \Charcoal\Action\AbstractAction;
use \Charcoal\Action\CronActionInterface;
use \Charcoal\Action\CronActionTrait;

/**
* @todo Rename AbstractCronAction
*/
abstract class CronAction extends AbstractAction implements CronActionInterface
{
    use CronActionTrait;

    /**
    * @param boolean $_use_lock
    */
    protected $_use_lock;

    /**
    * @param
    */
    private $_lock_fp;
    

    /**
    * @param array $data
    * @return CliAction Chainable
    */
    public function set_data(array $data)
    {
        //parent::set_data($data);
        $this->set_cron_data($data);

        return $this;
    }

    /**
    *
    */
    public function set_cron_data(array $data)
    {
        if (isset($data['use_lock']) && $data['use_lock']) {
            $this->set_use_lock($data['use_lock']);
        }
        return $this;
    }

    /**
    *
    */
    public function set_use_lock($use_lock)
    {
        $this->_use_lock = $use_lock;
        return $this;
    }

    /**
    *
    */
    public function use_lock()
    {
        return $this->_use_lock;
    }

    /**
    *
    */
    public function start_lock()
    {
        $lock_name = str_replace('\\', '-', get_class($this));
        $lock_file = sys_get_temp_dir().'/'.$lock_name;
        $this->_lock_fp = fopen($lock_file, 'w');
        if (!$this->_lock_fp) {
             throw new Exception('Can not run action. Lock file not available.');
        }
        if (flock($this->_lock_fp, LOCK_EX)) {
            return true;
        } else {
            throw new Exception('Can not run action. Lock file not available.');
        }
    }

    /**
    *
    */
    public function stop_lock()
    {
        if ($this->_lock_fp) {
            flock($this->_lock_fp, LOCK_UN);
            fclose($this->_lock_fp);
        }
    }

    abstract public function run();
}

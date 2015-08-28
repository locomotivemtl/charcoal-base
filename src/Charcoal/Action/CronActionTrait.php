<?php

namespace Charcoal\Action;

trait CronActionTrait
{
    /**
    * @var boolean $_use_lock
    */
    protected $_use_lock = false;

    /**
    * Lock file pointer
    * @var resource $_lock_fp
    */
    private $_lock_fp;
    
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
    * @throws Exception
    * @return boolean
    */
    public function start_lock()
    {
        $lock_name = str_replace('\\', '-', get_class($this));
        $lock_file = sys_get_temp_dir().'/'.$lock_name;
        $this->_lock_fp = fopen($lock_file, 'w');
        if (!$this->_lock_fp) {
             throw new Exception(
                 'Can not run action. Lock file not available.'
             );
        }
        if (flock($this->_lock_fp, LOCK_EX)) {
            return true;
        } else {
            throw new Exception(
                'Can not run action. Lock file not available.'
            );
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
}

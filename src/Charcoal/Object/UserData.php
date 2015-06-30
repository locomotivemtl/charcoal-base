<?php

namespace Charcoal\Object;

use \Datetime as Datetime;
use \InvalidArgumentException as InvalidArgumentException;

use \Charcoal\Object\AbstractObject as AbstractObject;
use \Charcoal\Object\UserDataInterface as UserDataInterface;

class UserData extends AbstractObject implements UserDataInterface
{
    /**
    * @var int $_ip
    */
    private $_ip;
    /**
    * @var string $_lang
    */
    private $_lang;
    /**
    * @var Datetime $_ts
    */
    private $_ts;

    /**
    * @param array $data
    * @return UserData Chainable
    */
    public function set_data(array $data)
    {
        //parent::set_data($data);
        if (isset($data['ip']) && $data['ip'] !== null) {
            $this->set_ip($data['ip']);
        }
        if (isset($data['lang']) && $data['lang'] !== null) {
            $this->set_lang($data['lang']);
        }
        if (isset($data['ts']) && $data['ts'] !== null) {
            $this->set_ts($data['ts']);
        }

        return $this;
    }

    public function set_ip($ip)
    {
        if (is_string($ip)) {
            $ip = ip2long($ip);
        } else if (is_int($ip)) {
            $ip = $ip;
        } else {
            throw new InvalidArgumentException('Ip must be a string or long integer');
        }
        $this->_ip = $ip;
        return $this;
    }

    public function ip()
    {
        return $this->_ip;
    }

    public function set_lang($lang)
    {
        if (!is_string($lang)) {
            throw new \InvalidArgumentException('Lang must be a string');
        }
        $this->_lang = $lang;
        return $this;
    }

    public function lang()
    {
        return $this->_lang;
    }

    public function set_ts($ts)
    {
        if (is_string($ts)) {
            $ts = new DateTime($ts);
        }
        if (!($ts instanceof DateTime)) {
            throw new InvalidArgumentException('Created must be a Datetime object or a valid datetime string');
        }
        $this->_ts = $ts;
        return $this;
    }

    public function ts()
    {
        return $this->_ts;
    }

    public function pre_save()
    {
        //parent::pre_save();

        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        //$lang = Charcoal::lang();
        $lang = '';
        $ts = 'now';

        $this->set_ip($ip);
        $this->set_lang($lang);
        $this->set_ts($ts);
    }
}

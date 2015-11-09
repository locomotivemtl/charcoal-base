<?php

namespace Charcoal\Object;

use \DateTime as DateTime;
use \DateTimeInterface as DateTimeInterface;
use \InvalidArgumentException as InvalidArgumentException;

// From `charcoal-core`
use \Charcoal\Model\AbstractModel;
use \Charcoal\Core\IndexableInterface;
use \Charcoal\Core\IndexableTrait;

// Local namespace dependencies
use \Charcoal\Object\UserDataInterface;

/**
*
*/
class UserData extends AbstractModel implements
    UserDataInterface,
    IndexableInterface
{
    use IndexableTrait;
    
    /**
    * @var int $_ip
    */
    private $_ip;
    /**
    * @var string $_lang
    */
    private $_lang;
    /**
    * @var DateTime $_ts
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

    /**
    * @param string|int $ip
    * @throws InvalidArgumentException
    * @return UserDataInterface Chainable
    */
    public function set_ip($ip)
    {
        if (is_string($ip)) {
            $ip = ip2long($ip);
        } elseif (is_int($ip)) {
            $ip = $ip;
        } else {
            throw new InvalidArgumentException('Ip must be a string or long integer');
        }
        $this->_ip = $ip;
        return $this;
    }

    /**
    * @return int
    */
    public function ip()
    {
        return $this->_ip;
    }

    /**
    * @param string $lang
    * @throws InvalidArgumentException
    * @return UserDataInterface Chainable
    */
    public function set_lang($lang)
    {
        if (!is_string($lang)) {
            throw new InvalidArgumentException('Lang must be a string');
        }
        $this->_lang = $lang;
        return $this;
    }

    /**
    * @return string
    */
    public function lang()
    {
        return $this->_lang;
    }

    /**
    * @param string|DateTime $ts
    * @throws InvalidArgumentException
    * @return UserDataInterface Chainable
    */
    public function set_ts($ts)
    {
        if (is_string($ts)) {
            $ts = new DateTime($ts);
        }
        if (!($ts instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Timestamp" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->_ts = $ts;
        return $this;
    }

    /**
    * @return DateTime|null
    */
    public function ts()
    {
        return $this->_ts;
    }

    /**
    * @return void
    */
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

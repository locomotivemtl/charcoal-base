<?php

namespace Charcoal\Object;

use \DateTime;
use \DateTimeInterface;
use \InvalidArgumentException;

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
     * @var int $ip
     */
    private $ip;
    /**
     * @var string $lang
     */
    private $lang;
    /**
     * @var DateTime $ts
     */
    private $ts;


    /**
     * @param integer $ip The remote IP at object creation.
     * @throws InvalidArgumentException If the IP argument is not a string or integer.
     * @return UserDataInterface Chainable
     */
    public function setIp($ip)
    {
        if (is_string($ip)) {
            $ip = ip2long($ip);
        } elseif (is_int($ip)) {
            $ip = $ip;
        } else {
            throw new InvalidArgumentException(
                'IP must be a string or long integer'
            );
        }
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return integer
     */
    public function ip()
    {
        return $this->ip;
    }

    /**
     * @param string $lang The language code (2-char).
     * @throws InvalidArgumentException If the argument is not a string.
     * @return UserDataInterface Chainable
     */
    public function setLang($lang)
    {
        if (!is_string($lang)) {
            throw new InvalidArgumentException(
                'Language must be a string'
            );
        }
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return string
     */
    public function lang()
    {
        return $this->lang;
    }

    /**
     * @param string|DateTime|null $ts Timestamp.
     * @throws InvalidArgumentException If the timestamp is not a valid date/time.
     * @return UserDataInterface Chainable
     */
    public function setTs($ts)
    {
        if ($ts === null) {
            $this->ts = null;
            return $this;
        }
        if (is_string($ts)) {
            try {
                $ts = new DateTime($ts);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    sprintf('Invalid timestamp (%s)', $e->getMessage())
                );
            }
        }
        if (!($ts instanceof DateTimeInterface)) {
            throw new InvalidArgumentException(
                'Invalid "Timestamp" value. Must be a date/time string or a DateTime object.'
            );
        }
        $this->ts = $ts;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function ts()
    {
        return $this->ts;
    }

    /**
     * @return void
     */
    public function preSave()
    {
        $ip = isset($SERVER['REMOTE_ADDR']) ? $SERVER['REMOTE_ADDR'] : '';
        $lang = '';
        $ts = 'now';

        $this->setIp($ip);
        $this->setLang($lang);
        $this->setTs($ts);
    }
}

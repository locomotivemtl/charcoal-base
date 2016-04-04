<?php

namespace Charcoal\Object;

use \DateTime;
use \DateTimeInterface;
use \InvalidArgumentException;

// Dependencies from 'charcoal-core'
use \Charcoal\Model\AbstractModel;
use \Charcoal\Core\IndexableInterface;
use \Charcoal\Core\IndexableTrait;

// Local dependencies
use \Charcoal\Object\UserDataInterface;

/**
 * The `UserData` Object
 *
 * _UserData_ objects are models typically submitted by the end-user of the application.
 */
class UserData extends AbstractModel implements
    UserDataInterface,
    IndexableInterface
{
    use IndexableTrait;

    /**
     * Client IP address of the end-user.
     *
     * @var integer
     */
    private $ip;

    /**
     * User agent identification of the end-user.
     *
     * The name and version of both the browser and operating system.
     *
     * @var string
     */
    private $userAgent;

    /**
     * Source URL or identifier of end-user submission.
     *
     * @var string
     */
    private $origin;

    /**
     * Language of the end-user or source URI.
     *
     * @var string
     */
    private $lang;

    /**
     * The creation timestamp.
     *
     * @var DateTime $ts
     */
    private $ts;

    /**
     * Set the client IP address.
     *
     * @param  integer $ip The remote IP at object creation.
     * @throws InvalidArgumentException If the IP is not a string or integer.
     * @return UserDataInterface Chainable
     */
    public function setIp($ip)
    {
        if ($ip !== null) {
            if (is_string($ip)) {
                $ip = ip2long($ip);
            } elseif (is_numeric($ip)) {
                $ip = (int)$ip;
            } else {
                $ip = 0;
            }
        }

        $this->ip = $ip;

        return $this;
    }

    /**
     * Retrieve the client IP address.
     *
     * @return integer
     */
    public function ip()
    {
        return $this->ip;
    }

    /**
     * Set the user-agent.
     *
     * @param  string $ua The client's user-agent identifier.
     * @throws InvalidArgumentException If the UA is not a string.
     * @return UserDataInterface Chainable
     */
    public function setUserAgent($ua)
    {
        if ($ua !== null) {
            if (is_string($ua)) {
                if ( strlen($ua) > 250 ) {
                    $ua = substr($ua, 0, 250);
                }
            } else {
                throw new InvalidArgumentException(
                    'User agent must be a string.'
                );
            }
        }

        $this->userAgent = $ua;

        return $this;
    }

    /**
     * Retrieve the client IP address.
     *
     * @return integer
     */
    public function userAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set the origin of the object submission.
     *
     * @param  string $origin The source URL or identifier of the submission.
     * @throws InvalidArgumentException If the origin is not a string.
     * @return UserDataInterface Chainable
     */
    public function setOrigin($origin)
    {
        if ($origin !== null) {
            if (!is_string($origin)) {
                throw new InvalidArgumentException(
                    'Origin must be a string.'
                );
            }
        }

        $this->origin = $origin;

        return $this;
    }

    /**
     * Retrieve the origin of the object submission.
     *
     * @return integer
     */
    public function origin()
    {
        return $this->origin;
    }

    /**
     * Set the language.
     *
     * @param  string $lang The language code.
     * @throws InvalidArgumentException If the argument is not a string.
     * @return UserDataInterface Chainable
     */
    public function setLang($lang)
    {
        if (!is_string($lang)) {
            throw new InvalidArgumentException(
                'Language must be a string.'
            );
        }

        $this->lang = $lang;

        return $this;
    }

    /**
     * Retrieve the language.
     *
     * @return string
     */
    public function lang()
    {
        return $this->lang;
    }

    /**
     * Set when the object was created.
     *
     * @param  DateTime|string $timestamp The timestamp at object's creation.
     * @throws InvalidArgumentException If the creation marker is invalid.
     * @return TimestampableInterface Chainable
     */
    public function setTs($timestamp)
    {
        if ($timestamp === false) {
            $timestamp = null;
        }

        if ($timestamp !== null) {
            if (is_string($timestamp)) {
                try {
                    $timestamp = new DateTime($timestamp);
                } catch (Exception $e) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid creation marker: %s', $e->getMessage())
                    );
                }
            }

            if (!($timestamp instanceof DateTimeInterface)) {
                throw new InvalidArgumentException(
                    'Invalid creation marker. Must be a date/time string or a DateTime object.'
                );
            }
        }

        $this->ts = $timestamp;

        return $this;
    }

    /**
     * Retrieve the creation timestamp.
     *
     * @return DateTime|null
     */
    public function ts()
    {
        return $this->ts;
    }

    /**
     * Event called before _creating_ the object.
     *
     * @see    StorableTrait::preSave() For the "create" Event.
     * @return boolean
     */
    public function preSave()
    {
        $result = parent::preSave();

        $ip   = (getenv('REMOTE_ADDR'] ?: '');
        $ua   = (getenv('HTTP_USER_AGENT') ?: '');
        $time = 'now';

        if (!isset($this->origin)) {
            $this->setOrigin(getenv('HTTP_HOST').getenv('REQUEST_URI'));
        }

        $this->setIp($ip);
        $this->setUserAgent($ua);
        $this->setTs($time);

        return $result;
    }
}

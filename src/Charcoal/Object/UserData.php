<?php

namespace Charcoal\Object;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use \InvalidArgumentException;

// From `charcoal-core`
use \Charcoal\Model\AbstractModel;

// Local namespace dependencies
use \Charcoal\Object\UserDataInterface;

/**
 * User Data is a base model for objects typically submitted by the end-user of the application.
 */
class UserData extends AbstractModel implements
    UserDataInterface
{
    /**
     * Client IP address of the end-user.
     *
     * @var integer
     */
    private $ip;

    /**
     * Language of the end-user or source URI.
     *
     * @var string
     */
    private $lang;

    /**
     * Source URL or identifier of end-user submission.
     *
     * @var string
     */
    private $origin;

    /**
     * Creation timestamp of submission.
     *
     * @var DateTime
     */
    private $ts;

    /**
     * Set the client IP address.
     *
     * @param  integer|null $ip The remote IP at object creation.
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
     * @return integer|null
     */
    public function ip()
    {
        return $this->ip;
    }

    /**
     * Set the origin language.
     *
     * @param  string $lang The language code.
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
     * Retrieve the language.
     *
     * @return string
     */
    public function lang()
    {
        return $this->lang;
    }

    /**
     * Set the origin of the object submission.
     *
     * @param  string $origin The source URL or identifier of the submission.
     * @throws InvalidArgumentException If the argument is not a string.
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
     * Resolve the origin of the user data.
     *
     * @return string
     */
    public function resolveOrigin()
    {
        $uri = 'http';

        if ( getenv('HTTPS') === 'on' ) {
            $uri .= 's';
        }

        $uri .= '://';
        $uri .= getenv('HTTP_HOST') . getenv('REQUEST_URI');

        return $uri;
    }

    /**
     * Retrieve the origin of the object submission.
     *
     * @return string
     */
    public function origin()
    {
        return $this->origin;
    }

    /**
     * Set when the object was created.
     *
     * @param  DateTime|string|null $timestamp The timestamp at object's creation. NULL is accepted and instances
     *     of DateTimeInterface are recommended; any other value will be converted (if possible) into one.
     * @throws InvalidArgumentException If the timestamp is invalid.
     * @return UserDataInterface Chainable
     */
    public function setTs($timestamp)
    {
        if ($timestamp !== null) {
            if (is_string($timestamp)) {
                try {
                    $timestamp = new DateTime($timestamp);
                } catch (Exception $e) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid timestamp: %s', $e->getMessage())
                    );
                }
            }

            if (!($timestamp instanceof DateTimeInterface)) {
                throw new InvalidArgumentException(
                    'Invalid timestamp value. Must be a date/time string or a DateTime object.'
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
     * @see    Charcoal\Source\StorableTrait::preSave() For the "create" Event.
     * @return boolean
     */
    public function preSave()
    {
        $result = parent::preSave();

        $this->setIp(getenv('REMOTE_ADDR') ? getenv('REMOTE_ADDR') : '');
        $this->setTs('now');

        if (!isset($this->lang)) {
            $this->setLang('');
        }

        if (!isset($this->origin)) {
            $this->setOrigin($this->resolveOrigin());
        }

        return $result;
    }
}

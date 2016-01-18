<?php

namespace Charcoal\Object;

/**
 *
 */
interface UserDataInterface
{

    /**
     * @param integer $ip The IP at object creation.
     * @return UserDataInterface Chainable
     */
    public function setIp($ip);

    /**
     * @return integer
     */
    public function ip();

    /**
     * @param string|DateTime $ts The time of the object creation.
     * @return UserDataInterface Chainable
     */
    public function setTs($ts);

    /**
     * @return DateTime
     */
    public function ts();

    /**
     * @param string $lang The language code upon creation.
     * @return UserDataInterface Chainable
     */
    public function setLang($lang);

    /**
     * @return string
     */
    public function lang();
}

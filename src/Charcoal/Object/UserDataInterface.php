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
    public function set_ip($ip);

    /**
     * @return integer
     */
    public function ip();

    /**
     * @param string|DateTime $ts The time of the object creation.
     * @return UserDataInterface Chainable
     */
    public function set_ts($ts);

    /**
     * @return DateTime
     */
    public function ts();

    /**
     * @param string $lang The language code upon creation.
     * @return UserDataInterface Chainable
     */
    public function set_lang($lang);

    /**
     * @return string
     */
    public function lang();
}

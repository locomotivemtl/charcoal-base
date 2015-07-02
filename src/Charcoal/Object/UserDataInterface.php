<?php

namespace Charcoal\Object;

interface UserDataInterface
{
    /**
    * @param string|int $ip
    * @return UserDataInterface Chainable
    */
    public function set_ip($ip);

    /**
    * @return int
    */
    public function ip();

    /**
    * @param string|Datetime $ts
    * @return UserDataInterface Chainable
    */
    public function set_ts($ts);

    /**
    * @return DateTime
    */
    public function ts();

    /**
    * @param string $lang
    * @return UserDataInterface Chainable
    */
    public function set_lang($lang);

    /**
    * @return string
    */
    public function lang();
}

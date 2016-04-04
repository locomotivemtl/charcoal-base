<?php

namespace Charcoal\Object;

/**
 * The `UserData` Object
 *
 * _UserData_ objects are models typically submitted by the end-user of the application.
 *
 * Examples of `UserData` objects: _Inquiry_, _Newsletter Subscription_, _Blog Comment_,
 * _Answer to a Survey_, _Product Rating or Review_, etc.
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

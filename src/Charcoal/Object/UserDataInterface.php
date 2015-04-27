<?php

namespace Charcoal\Object;

interface UserDataInterface
{
    public function set_ip($ip);
    public function ip();

    public function set_ts($ts);
    public function ts();

    public function set_lang($lang);
    public function lang();
}

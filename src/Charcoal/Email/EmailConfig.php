<?php

namespace Charcoal\Email;

use \Charcoal\Config\AbstractConfig as AbstractConfig;

class EmailConfig extends AbstractConfig
{
    /**
    * @var boolean $_smtp
    */
    private $_smtp;
    /**
    * @var string $_default_from
    */
    private $_default_from;
    /**
    * @var string $_default_reply_to
    */
    private $_default_reply_to;
    /**
    * @var boolean $_default_track
    */
    private $_default_track;
    /**
    * @var boolean $_default_log
    */
    private $_default_log;

    /**
    *
    */
    public function default_data()
    {
        return [
            'smtp'=>false,
            'smtp_options'=>[],
            'default_from'=>null,
            'default_reply_to'=>null,
            'default_track'=>false,
            'default_log'=>true
        ];
    }

    public function set_data($data)
    {
        // @todo
    }

    /**
    * @param mixed $default_from
    */
    public function set_default_from($default_from)
    {
        $this->_default_from = $default_from;
        return $this;
    }

    /**
    * @return string
    */
    public function default_from()
    {
        return $this->_default_from;
    }
}

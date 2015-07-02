<?php

namespace Charcoal\Email;

// From `PHP`
use \InvalidArgumentException as InvalidArgumentException;

// From `charcoal-core`
use \Charcoal\Config\AbstractConfig as AbstractConfig;

class EmailConfig extends AbstractConfig
{
    /**
    * @var boolean $_smtp
    */
    private $_smtp;
    /**
    * @var array $smtp_options;
    */
    private $_smtp_options;
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
    * @return array
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

    /**
    * @param array $data
    * @return EmailConfig Chainable
    */
    public function set_data(array $data)
    {

        if (isset($data['smtp']) && $data['smtp'] !== null) {
            $this->set_smtp($data['smtp']);
        }
        if (isset($data['smtp_options']) && $data['smtp_options'] !== null) {
            $this->set_smtp_options($data['smtp_options']);
        }
        if (isset($data['default_from']) && $data['default_from'] !== null) {
            $this->set_default_from($data['default_from']);
        }
        if (isset($data['default_reply_to']) && $data['default_reply_to'] !== null) {
            $this->set_default_reply_to($data['default_reply_to']);
        }
        if (isset($data['default_track']) && $data['default_track'] !== null) {
            $this->set_default_track($data['default_track']);
        }
        if (isset($data['default_log']) && $data['default_log'] !== null) {
            $this->set_default_log($data['default_log']);
        }

        return $this;
    }

    /**
    * @param boolean $smtp
    * @throws InvalidArgumentException if parameter is not boolean
    * @return EmailConfig Chainable
    */
    public function set_smtp($smtp)
    {
        if (!is_bool($smtp)) {
            throw new InvalidArgumentException('Smtp needs to be boolean');
        }
        $this->_smtp = $smtp;
        return $this;
    }

    /**
    * @return bool
    */
    public function smtp()
    {
        return $this->_smtp;
    }

    /**
    * @param array $smtp_options
    * @return EmailConfig Chainable
    */
    public function set_smtp_options(array $smtp_options)
    {
        $this->_smtp_options = $smtp_options;
        return $this;
    }

    /**
    * @return array
    */
    public function smtp_options()
    {
        return $this->_smtp_options;
    }

    /**
    * @param mixed $default_from
    * @return EmailConfig Chainable
    */
    public function set_default_from($default_from)
    {
        if (is_array($default_from)) {
            $default_from = $this->email_from_array($default_from);
        }
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

    /**
    * @param mixed $default_reply_to
    * @return EmailConfig Chainable
    */
    public function set_default_reply_to($default_reply_to)
    {
        if (is_array($default_reply_to)) {
            $default_reply_to = $this->email_from_array($default_reply_to);
        }
        $this->_default_reply_to = $default_reply_to;
        return $this;
    }

    /**
    * @return string
    */
    public function default_reply_to()
    {
        return $this->_default_reply_to;
    }

    /**
    * @param boolean $default_log
    * @throws InvalidArgumentException if parameter is invalid
    * @return EmailConfig Chainable
    */
    public function set_default_log($default_log)
    {
        if (!is_bool($default_log)) {
            throw new InvalidArgumentException('Parameter needs to be  a boolean');
        }
        $this->_default_log = $default_log;
        return $this;
    }

    /**
    * @return boolean
    */
    public function default_log()
    {
        return $this->_default_log;
    }

    /**
    * @param boolean $default_track
    * @throws InvalidArgumentException if parameter is invalid
    * @return EmailConfig Chainable
    */
    public function set_default_track($default_track)
    {
        if (!is_bool($default_track)) {
            throw new InvalidArgumentException('Parameter needs to be  a boolean');
        }
        $this->_default_track = $default_track;
        return $this;
    }

    /**
    * @return boolean
    */
    public function default_track()
    {
        return $this->_default_track;
    }

    /**
    * @param array $email_array
    * @throws InvalidArgumentException if parameter is not an array or invalid array
    * @return string
    */
    protected function email_from_array($email_array)
    {
        if (!is_array($email_array)) {
            throw new InvalidArgumentException('Parameter must be an array');
        }

        if (!isset($email_array['email'])) {
            throw new InvalidArgumentException('Array must atleast contain the email key');
        }

        $email = filter_var($email_array['email'], FILTER_SANITIZE_EMAIL);
        if (!isset($email_array['name'])) {
            return $email;
        }
        
        $name = str_replace('"', '', filter_var($email_array['name'], FILTER_SANITIZE_STRING));
        return '"'.$name.'" <'.$email.'>';
    }
}

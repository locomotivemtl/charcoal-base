<?php

namespace Charcoal\Email;

// From `charcoal-core`
use \Charcoal\Config\ConfigurableInterface as ConfigurableInterface;
use \Charcoal\Config\ConfigurableTrait as ConfigurableTrait;

// From `charcoal-base`
use \Charcoal\Email\EmailInterface as EmailInterface;

/**
* Default implementaiton, as abstract class, of the `EmailInterface`.
*/
abstract class AbstractEmail implements
    EmailInterface,
    ConfigurableInterface
{
    use ConfigurableTrait;

    /**
    * @var string
    */
    private $_campaign;
    private $_to = [];
    private $_cc = [];
    private $_bcc = [];
    private $_from;
    private $_reply_to;
    private $_subject;
    private $_msg_html;
    private $_msg_txt;
    private $_attachments = [];
    private $_log;
    private $_track;

    /**
    * @param array $data
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_data($data)
    {
        if(!is_array($data)) {
            throw new \InvalidArgumentException('Data must be an array');
        }

        if(isset($data['campaign']) && $data['campaign'] !== null) {
            $this->set_campaign($data['campaign']);
        }
        if(isset($data['to']) && $data['to'] !== null) {
            $this->set_to($data['to']);
        }
        if(isset($data['cc']) && $data['cc'] !== null) {
            $this->set_cc($data['cc']);
        }
        if(isset($data['bcc']) && $data['bcc'] !== null) {
            $this->set_bcc($data['bcc']);
        }
        if(isset($data['from']) && $data['from'] !== null) {
            $this->set_from($data['from']);
        }
        if(isset($data['reply_to']) && $data['reply_to'] !== null) {
            $this->set_reply_to($data['reply_to']);
        }
        if(isset($data['subject']) && $data['subject'] !== null) {
            $this->set_subject($data['subject']);
        }
        if(isset($data['msg_html']) && $data['msg_html'] !== null) {
            $this->set_msg_html($data['msg_html']);
        }
        if(isset($data['msg_txt']) && $data['msg_txt'] !== null) {
            $this->set_($data['msg_txt']);
        }
        if(isset($data['attachments']) && $data['attachments'] !== null) {
            $this->set_attachments($data['attachments']);
        }
        if(isset($data['log']) && $data['log'] !== null) {
            $this->set_log($data['log']);
        }
        if(isset($data['track']) && $data['track'] !== null) {
            $this->set_track($data['track']);
        }
        return $this;
    }
    /**
    * @param string $campaign
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_campaign($campaign)
    {
        if(!is_string($campaign)) {
            throw new \InvalidArgumentException('Campaign must be a string');
        }
        $this->_campaign = $campaign;
        return $this;
    }

    /**
    * @return string
    */
    public function campaign()
    {
        if($this->_campaign === null) {
            $this->_campaign = $this->generate_campaign();
        }
        return $this->_campaign;
    }

    /**
    * @return string
    */
    protected function generate_campaign()
    {
        return uniqid();
    }

    /**
    * @param string|array $to
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_to($to)
    {
        if(is_string($to)) {
            $to = [$to];
        }
        if(!is_array($to)) {
            throw new \InvalidArgumentException('To must be an array of recipients');
        }
        foreach($to as $t) {
            $this->add_to($t);
        }
        return $this;
    }

    /**
    * @param mixed $to
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function add_to($to)
    {
        if(is_string($to)) {
            $this->_to[] = $to;
        }
        else if(is_array($to)) {
            $this->_to[] = $this->email_from_array($to);
        }
        else {
            throw new \InvalidArgumentException('Email address must be an array or a string');
        }
        return $this;
    }

    /**
    * @return array Recipients email (as array of strings)
    */
    public function to()
    {
        return $this->_to;
    }

    /**
    * @param string|array $cc
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_cc($cc)
    {
        if(is_string($cc)) {
            $cc = [$cc];
        }
        if(!is_array($cc)) {
            throw new \InvalidArgumentException('CC must be an array of recipients');
        }
        foreach($cc as $t) {
            $this->add_cc($t);
        }
        return $this;
    }

    /**
    * @param mixed $cc
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function add_cc($cc)
    {
        if(is_string($cc)) {
            $this->_cc[] = $cc;
        }
        else if(is_array($cc)) {
            $this->_cc[] = $this->email_from_array($cc);
        }
        else {
            throw new \InvalidArgumentException('Email address must be an array or a string');
        }
        return $this;
    }

    /**
    * @return array
    */
    public function cc()
    {
        return $this->_cc;
    }

    /**
    * @param string|array $bcc
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_bcc($bcc)
    {
        if(is_string($bcc)) {
            $bcc = [$bcc];
        }
        if(!is_array($bcc)) {
            throw new \InvalidArgumentException('BCC must be an array of recipients');
        }
        foreach($bcc as $t) {
            $this->add_bcc($t);
        }
        return $this;
    }

    /**
    * @param mixed $bcc
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function add_bcc($bcc)
    {
        if(is_string($bcc)) {
            $this->_bcc[] = $bcc;
        }
        else if(is_array($bcc)) {
            $this->_bcc[] = $this->email_from_array($bcc);
        }
        else {
            throw new \InvalidArgumentException('Email address must be an array or a string');
        }
        return $this;
    }

    /**
    * @return array
    */
    public function bcc()
    {
        return $this->_bcc;
    }

    /**
    * @param mixed $from
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_from($from)
    {
        if(is_string($from)) {
            $this->_from = $from;
        }
        else if(is_array($from)) {
            $this->_from = $this->email_from_array($from);
        }
        else {
            throw new \InvalidArgumentException('Email address must be an array or a string');
        }
        return $this;
    }

    /**
    * @return string
    */
    public function from()
    {
        if($this->_from === null) {
            $this->_from = $this->config()->default_from();
        }
        return $this->_from;
    }

    /**
    * Set the "reply-to" header field.
    *
    * @param mixed $reply_to
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_reply_to($reply_to)
    {
        if(is_string($reply_to)) {
            $this->_reply_to = $reply_to;
        }
        else if(is_array($reply_to)) {
            $this->_reply_to = $this->email_from_array($reply_to);
        }
        else {
            throw new \InvalidArgumentException('Email address must be an array or a string');
        }
        return $this;
    }

    /**
    * @return string
    */
    public function reply_to()
    {
        return $this->_reply_to;
    }

    /**
    * @param string $subject
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_subject($subject)
    {
        if(!is_string($subject)) {
            throw new \InvalidArgumentException('Subject needs to be a string');
        }
        $this->_subject = $subject;
        return $this;
    }
    
    /**
    * @return string
    */
    public function subject()
    {
        return $this->_subject;
    }

    /**
    * @param string $msg_html
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_msg_html($msg_html)
    {
        if(!is_string($msg_html)) {
            throw new \InvalidArgumentException('HTML msg must be a string');
        }
        $this->_msg_html = $msg_html;
        return $this;
    }

    /**
    * @return string
    */
    public function msg_html()
    {
        if($this->_msg_html === null) {
            $this->_msg_html = $this->generate_msg_html();
        }
        return $this->_msg_html;
    }

    /**
    *
    */
    abstract protected function generate_msg_html();


    /**
    * @param string $msg_txt
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_msg_txt($msg_txt)
    {
        if(!is_string($msg_txt)) {
            throw new \InvalidArgumentException('Text msg must be a string');
        }
        $this->_msg_html = $msg_txt;
        return $this;
    }

    /**
    * @return string
    */
    public function msg_txt()
    {
        if($this->_msg_txt === null) {
            $this->_msg_txt = $this->generate_msg_txt();
        }
        return $this->_msg_txt;
    }

    abstract protected function generate_msg_txt();

    /**
    * @var array $attachments
    * @throws \InvalidArgumentException if parameter is invalid
    * @return EmailInterface Chainable
    */
    public function set_attachments($attachments)
    {
        if(!is_array($attachments)) {
            throw new \InvalidArgumentException('Attachments need to be an array');
        }
        foreach($attachments as $att) {
            $this->add_attachment($att);
        }
        return $this;
    }

    /**
    * @var mixed $attachment
    * @return EmailInterface Chainable
    */
    public function add_attachment($attachment)
    {
        $this->_attachments[] = $attachment;
        return $this;
    }

    /**
    * @return array
    */
    public function attachments()
    {
        return $this->_attachments;
    }

    /**
    * Enable or disable logging for this particular email.
    *
    * @param bool $log
    * @throws \InvalidArgumentException if parameter is not boolean
    * @return EmailInterface Chainable
    */
    public function set_log($log)
    {
        if(!is_bool($track)) {
            throw new \InvalidArgumentException('Track must be boolean');
        }
        $this->_log = $log;
        return $this;
    }

    /**
    * @return bool
    */
    public function log()
    {
        if($this->_log === null) {
            $this->_log = $this->config()->default_log();
        }
        return $this->_log;
    }

    /**
    * Enable or disable traking for this particular email.
    *
    * @param bool $track
    * @throws \InvalidArgumentException if track is not a boolean
    * @return EmailInterface Chainable
    */
    public function set_track($track)
    {
        if(!is_bool($track)) {
            throw new \InvalidArgumentException('Track must be boolean');
        }
        $this->_track = $track;
        return $this;
    }

    /**
    * @return bool
    */
    public function track()
    {
        if($this->_track === null) {
            $this->_track = $this->config()->default_track();
        }
        return $this->_track;
    }

    /**
    * Send the email to all recipients.
    *
    * @return boolean Success / Failure
    */
    abstract public function send();

    /**
    * Add the email to the queue pool.
    *
    * @return boolean Success / Failure
    */
    abstract public function queue();

    /**
    * Log the "send" event
    * @return mixed The (created) log object
    */
    abstract protected function send_log();

    /**
    * Log the "queue" event
    * @return mixed The (created) log object
    */
    abstract protected function queue_log();

    /**
    * @param array
    * @throws \InvalidArgumentException if parameter is not an array or invalid array
    * @return string
    */
    protected function email_from_array($email_array)
    {
        if(!is_array($email_array)) {
            throw new \InvalidArgumentException('Parameter must be an array');
        }

        if(!isset($email_array['email'])) {
            throw new \InvalidArgumentException('Array must atleast contain the email key');
        }

        $email = filter_var($email_array['email'], FILTER_SANITIZE_EMAIL);
        if(!isset($email_array['name'])) {
            return $email;
        }
        
        $name = str_replace('"', '', filter_var($email_array['name'], FILTER_SANITIZE_STRING));
        return '"'.$name.'" <'.$email.'>';
    }

    /**
    * ConfigurableInterface > create_config()
    */
    public function create_config($data=null)
    {
        $config = new EmailConfig();
        if($data !== null) {
            $config->set_data($data);
        }
        return $config;
    }
}

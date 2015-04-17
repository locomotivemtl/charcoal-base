<?php

namespace Charcoal\Email;

interface EmailInterface
{
    /**
    * @param array $data
    * @return EmailInterface Chainable
    */
    public function set_data($data);

    /**
    * @param string $campaign
    * @return EmailInterface Chainable
    */
    public function set_campaign($campaign);

    /**
    * @return string
    */
    public function campaign();

    /**
    * @param mixed $to
    * @return EmailInterface Chainable
    */
    public function set_to($to);

    /**
    * @param mixed $to
    * @return EmailInterface Chainable
    */
    public function add_to($to);

    /**
    * @return array
    */
    public function to();

    /**
    * @param mixed $cc
    * @return EmailInterface Chainable
    */
    public function set_cc($cc);

    /**
    * @param mixed $cc
    * @return EmailInterface Chainable
    */
    public function add_cc($cc);

    /**
    * @return array
    */
    public function cc();

     /**
    * @param mixed $bcc
    * @return EmailInterface Chainable
    */
    public function set_bcc($bcc);

    /**
    * @param mixed $bcc
    * @return EmailInterface Chainable
    */
    public function add_bcc($bcc);

    /**
    * @return array
    */
    public function bcc();

    /**
    * @param mixed $from
    * @return EmailInterface Chainable
    */
    public function set_from($from);

    /**
    * @return string
    */
    public function from();

    /**
    * Set the "reply-to" header field.
    *
    * @param mixed $reply_to
    * @return EmailInterface Chainable
    */
    public function set_reply_to($reply_to);

    /**
    * @return string
    */
    public function reply_to();

    /**
    * @param string $subject
    * @return EmailInterface Chainable
    */
    public function set_subject($subject);
    
    /**
    * @return string
    */
    public function subject();

    /**
    * @param string $msg_html
    * @return EmailInterface Chainable
    */
    public function set_msg_html($msg_html);

    /**
    * @return string
    */
    public function msg_html();

    /**
    * @param string $msg_txt
    * @return EmailInterface Chainable
    */
    public function set_msg_txt($msg_txt);

    /**
    * @return string
    */
    public function msg_txt();

    /**
    * @var array $attachments
    * @return EmailInterface Chainable
    */
    public function set_attachments($attachments);

    /**
    * @var mixed $attachment
    * @return EmailInterface Chainable
    */
    public function add_attachment($attachment);

    /**
    * @return array
    */
    public function attachments();

    /**
    * Enable or disable logging for this particular email.
    *
    * @param bool $log
    * @return EmailInterface Chainable
    */
    public function set_log($log);

    /**
    * @return bool
    */
    public function log();

    /**
    * Enable or disable traking for this particular email.
    *
    * @param bool $track
    * @return EmailInterface Chainable
    */
    public function set_track($track);

    /**
    * @return bool
    */
    public function track();

    /**
    * Send the email to all recipients.
    *
    * @return boolean Success / Failure
    */
    public function send();

    /**
    * Add the email to the queue pool.
    *
    * @return boolean Success / Failure
    */
    public function queue();

}

<?php

namespace Charcoal\Email;

use \Charcoal\Email\AbstractEmail as AbstractEmail;

/**
* Concrete implementation of Email class
*/
class Email extends AbstractEmail
{
    protected function generate_msg_html()
    {
        return '';
    }

    protected function generate_msg_txt()
    {
        return '';
    }

    public function send()
    {

    }

    public function queue()
    {

    }

    protected function send_log()
    {

    }

    protected function queue_log()
    {

    }
}

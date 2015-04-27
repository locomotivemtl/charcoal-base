<?php

namespace Charcoals\Tests\Email;

use \Charcoal\Email\EmailLog as EmailLog;

class EmailLogTest extends \PHPUnit_Framework_Testcase
{
    public function testConstructor()
    {
        $obj = new EmailLog();
        $this->assertInstanceOf('Charcoal\Email\EmailLog', $obj);
    }
}

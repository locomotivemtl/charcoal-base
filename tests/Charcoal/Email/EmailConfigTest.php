<?php

namespace Charcoals\Tests\Email;

use \Charcoal\Email\EmailConfig as EmailConfig;

class EmailConfigTest extends \PHPUnit_Framework_Testcase
{
    public function testConstructor()
    {
        $obj = new EmailConfig();
        $this->assertInstanceOf('Charcoal\Email\EmailConfig', $obj);
    }
}

<?php

namespace Charcoals\Tests\Email;

use \Charcoal\Email\EmailQueue as EmailQueue;

class EmailQueueTest extends \PHPUnit_Framework_Testcase
{
    public function testConstructor()
    {
        $obj = new EmailQueue();
        $this->assertInstanceOf('Charcoal\Email\EmailQueue', $obj);
    }
}

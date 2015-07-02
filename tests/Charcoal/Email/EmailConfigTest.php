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

    public function testSetData()
    {
        $obj = new EmailConfig();
        $data = [
            'smtp'=>true,
            'smtp_options'=>[
                'server'=>'localhost'
            ],
            'default_from'=>'test@example.com',
            'default_reply_to'=>[
                'name'=>'Test',
                'email'=>'test@example.com'
            ],
            'default_log'=>true,
            'default_track'=>true
        ];
        $ret = $obj->set_data($data);
        $this->assertSame($ret, $obj);
        $this->assertEquals(true, $obj->smtp());
        $this->assertEquals(['server'=>'localhost'], $obj->smtp_options());
        $this->assertEquals('test@example.com', $obj->default_from());
        $this->assertEquals('"Test" <test@example.com>', $obj->default_reply_to());
        $this->assertEquals(true, $obj->default_log());
        $this->assertEquals(true, $obj->default_track());
    }

    public function testSetSmtp()
    {
        $obj = new EmailConfig();
        $ret = $obj->set_smtp(true);
        $this->assertSame($ret, $obj);
        $this->assertEquals(true, $obj->smtp());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_smtp('foo');
    }

    public function testSetSmtpOptions()
    {
        $obj = new EmailConfig();
        $ret = $obj->set_smtp_options([
                'server'=>'localhost'
        ]);
        $this->assertSame($ret, $obj);
        $this->assertEquals(['server'=>'localhost'], $obj->smtp_options());

    }

    public function testSetDefaultFrom()
    {
        $obj = new EmailConfig();
        $ret = $obj->set_default_from('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals('test@example.com', $obj->default_from());

        $obj->set_default_from(
            [
            'name'=>'Test',
            'email'=>'test@example.com'
            ]
        );
        $this->assertEquals('"Test" <test@example.com>', $obj->default_from());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_default_log(123);
    }

    public function testSetDefaultReplyTo()
    {
        $obj = new EmailConfig();
        $ret = $obj->set_default_reply_to('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals('test@example.com', $obj->default_reply_to());

        $obj->set_default_reply_to(
            [
            'name'=>'Test',
            'email'=>'test@example.com'
            ]
        );
        $this->assertEquals('"Test" <test@example.com>', $obj->default_reply_to());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_default_log(123);
    }

    public function testSetDefaultLog()
    {
        $obj = new EmailConfig();
        $ret = $obj->set_default_log(true);
        $this->assertSame($ret, $obj);
        $this->assertEquals(true, $obj->default_log());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_default_log('foo');
    }

    public function testSetDefaultTrack()
    {
        $obj = new EmailConfig();
        $ret = $obj->set_default_track(true);
        $this->assertSame($ret, $obj);
        $this->assertEquals(true, $obj->default_track());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_default_track('foo');
    }
}

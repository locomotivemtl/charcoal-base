<?php

namespace Charcoals\Tests\Email;

use \Charcoal\Email\Email as Email;

/**
* Test the AbstractEmail methods, through concrete `Email` class.
*/
class AbstractEmailTest extends \PHPUnit_Framework_Testcase
{
    public function testConstructor()
    {
        $obj = new Email();
        $this->assertInstanceOf('Charcoal\Email\Email', $obj);
    }

    public function testSetData()
    {
        $obj = new Email();
        $ret = $obj->set_data([
            'campaign'=>'foo',
            'to'=>'test@example.com',
            'cc'=>'cc@example.com',
            'bcc'=>'bcc@example.com',
            'from'=>'from@example.com',
            'reply_to'=>'reply@example.com',
            'subject'=>'bar',
            'msg_html'=>'foo',
            'msg_txt'=>'baz',
            'attachments'=>[
                'foo'
            ],
            'log'=>true,
            'track'=>true
        ]);
        $this->assertSame($ret, $obj);

        $this->assertEquals('foo', $obj->campaign());
        $this->assertEquals(['test@example.com'], $obj->to());
        $this->assertEquals(['cc@example.com'], $obj->cc());
        $this->assertEquals(['bcc@example.com'], $obj->bcc());
        $this->assertEquals('from@example.com', $obj->from());
        $this->assertEquals('reply@example.com', $obj->reply_to());
        $this->assertEquals('bar', $obj->subject());
        $this->assertEquals('foo', $obj->msg_html());
        $this->assertEquals('baz', $obj->msg_txt());
        $this->assertEquals(['foo'], $obj->attachments());
        $this->assertEquals(true, $obj->log());
        $this->assertEquals(true, $obj->track());

        # $this->setExpectedException('\InvalidArgumentException');
        $this->setExpectedException('\PHPUnit_Framework_Error');
        $obj->set_data(false);
    }

    public function testSetCampaign()
    {
        $obj = new Email();
        $ret = $obj->set_campaign('foo');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->campaign());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_campaign([1, 2, 3]);
    }

    public function testGenerateCampaign()
    {
        $obj = new Email();
        $ret = $obj->campaign();
        $this->assertNotEmpty($ret);
    }

    public function testSetTo()
    {
        $obj = new Email();

        $ret = $obj->set_to(['test@example.com']);
        $this->assertSame($ret, $obj);
        $this->assertEquals(['test@example.com'], $obj->to());

        $obj->set_to([[
            'name'=>'Test',
            'email'=>'test@example.com'
        ]]);
        $this->assertEquals(['"Test" <test@example.com>'], $obj->to());

        $obj->set_to('test@example.com');
        $this->assertEquals(['test@example.com'], $obj->to());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_to(false);
    }

    public function testAddTo()
    {
        $obj = new Email();
        $ret = $obj->add_to('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals(['test@example.com'], $obj->to());

        $obj->add_to(['name'=>'Test','email'=>'test@example.com']);
        $this->assertEquals(['test@example.com', '"Test" <test@example.com>'], $obj->to());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->add_to(false);
    }

    public function testSetCc()
    {
        $obj = new Email();

        $ret = $obj->set_cc(['test@example.com']);
        $this->assertSame($ret, $obj);
        $this->assertEquals(['test@example.com'], $obj->cc());

        $obj->set_cc([[
            'name'=>'Test',
            'email'=>'test@example.com'
        ]]);
        $this->assertEquals(['"Test" <test@example.com>'], $obj->cc());

        $obj->set_cc('test@example.com');
        $this->assertEquals(['test@example.com'], $obj->cc());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_cc(false);
    }

    public function testAddCc()
    {
        $obj = new Email();
        $ret = $obj->add_cc('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals(['test@example.com'], $obj->cc());

        $obj->add_cc(['name'=>'Test','email'=>'test@example.com']);
        $this->assertEquals(['test@example.com', '"Test" <test@example.com>'], $obj->cc());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->add_cc(false);
    }

    public function testSetBcc()
    {
        $obj = new Email();

        $ret = $obj->set_bcc(['test@example.com']);
        $this->assertSame($ret, $obj);
        $this->assertEquals(['test@example.com'], $obj->bcc());

        $obj->set_bcc([[
            'name'=>'Test',
            'email'=>'test@example.com'
        ]]);
        $this->assertEquals(['"Test" <test@example.com>'], $obj->bcc());

        $obj->set_bcc('test@example.com');
        $this->assertEquals(['test@example.com'], $obj->bcc());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_bcc(false);
    }

    public function testAddBcc()
    {
        $obj = new Email();
        $ret = $obj->add_bcc('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals(['test@example.com'], $obj->bcc());

        $obj->add_bcc(['name'=>'Test','email'=>'test@example.com']);
        $this->assertEquals(['test@example.com', '"Test" <test@example.com>'], $obj->bcc());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->add_bcc(false);
    }

    public function testSetFrom()
    {
        $obj = new Email();
        //$config = $obj->config()->set_default_from('default@example.com');
        //$this->assertEquals('default@example.com', $obj->from());

        $ret = $obj->set_from('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals('test@example.com', $obj->from());

        $obj->set_from([
            'name'=>'Test',
            'email'=>'test@example.com'
        ]);
        $this->assertEquals('"Test" <test@example.com>', $obj->from());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_from(false);
    }

    public function testSetReplyTo()
    {
        $obj = new Email();
        //$config = $obj->config()->set_default_reply_to('default@example.com');
        //$this->assertEquals('default@example.com', $obj->reply_to());

        $ret = $obj->set_reply_to('test@example.com');
        $this->assertSame($ret, $obj);
        $this->assertEquals('test@example.com', $obj->reply_to());

        $obj->set_reply_to([
            'name'=>'Test',
            'email'=>'test@example.com'
        ]);
        $this->assertEquals('"Test" <test@example.com>', $obj->reply_to());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_reply_to(false);
    }

    public function testSetSubject()
    {
        $obj = new Email();
        $ret = $obj->set_subject('foo');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->subject());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_subject(null);
    }

    public function testSetMsgHtml()
    {
        $obj = new Email();
        $ret = $obj->set_msg_html('foo');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->msg_html());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_msg_html(null);
    }

    public function testSetMsgText()
    {
        $obj = new Email();
        $ret = $obj->set_msg_txt('foo');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foo', $obj->msg_txt());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_msg_txt(null);
    }

    public function testSetAttachments()
    {
        $obj = new Email();
        $ret = $obj->set_attachments(['foo']);
        $this->assertSame($ret, $obj);
        $this->assertEquals(['foo'], $obj->attachments());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_attachments(false);
    }

    public function testSetLog()
    {
        $obj = new Email();
        // $this->config()->set_default_log(false);
        // $this->assertNotTrue($obj->log());

        $ret = $obj->set_log(true);
        $this->assertSame($ret, $obj);
        $this->assertTrue($obj->log());

        $obj->set_log(false);
        $this->assertNotTrue($obj->log());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_log('foo');
    }

    public function testSetTrack()
    {
        $obj = new Email();
        // $this->config()->set_default_track(false);
        // $this->assertNotTrue($obj->track());

        $ret = $obj->set_track(true);
        $this->assertSame($ret, $obj);
        $this->assertTrue($obj->track());

        $obj->set_track(false);
        $this->assertNotTrue($obj->track());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_track('foo');
    }
}

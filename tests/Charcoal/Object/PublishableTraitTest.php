<?php

namespace Charcoal\Tests\Object;

use \DateTime;

use \Charcoal\Object\PublishableTrait as PublishableTrait;

/**
*
*/
class PublishableTraitTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    /**
    * Create mock object from trait.
    */
    public function setUp()
    {
        $this->obj = $this->getMockForTrait('\Charcoal\Object\PublishableTrait');
    }

    /**
    * Assert that the `set_publishable_data` method:
    * - is chainable
    * - sets the publish_date and expiry_date
    */
    public function testSetPublishableData()
    {
        $obj = $this->obj;
        $ret = $obj->set_publishable_data([
            'publish_date'=>'yesterday',
            'expiry_date'=>'tomorrow'
        ]);
        $this->assertSame($ret, $obj);

        $this->assertEquals(new DateTime('yesterday'), $obj->publish_date());
        $this->assertEquals(new DateTime('tomorrow'), $obj->expiry_date());
    }

     /**
    * Assert that the `set_publish_date` method:
    * - is chainable
    * - sets the publish_date value when a string is passed
    * - sets the publish_date value when a DateTime is passed
    * - throws an InvalidArgumentException if other types of arguments are passed
    */
    public function testSetPublishDate()
    {
        $obj = $this->obj;
        $dt = new DateTime('2015-01-01 00:00:00');

        $ret = $obj->set_publish_date('2015-01-01 00:00:00');
        $this->assertSame($ret, $obj);
        $this->assertEquals($dt, $obj->publish_date());

        $obj->set_publish_date($dt);
        $this->assertEquals($dt, $obj->publish_date());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_publish_date(false);
    }

    /**
    * Assert that the `set_expiry_date` method:
    * - throws an InvalidArgumentException if a non-ts string value is passed
    */
    public function testSetPublishDateBogusStringThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $obj = $this->obj;
        $obj->set_publish_date('foobar');
    }

    /**
    * Assert that the `set_expiry_date` method:
    * - is chainable
    * - sets the expiry_date value when a string is passed
    * - sets the expiry_date value when a DateTime is passed
    * - throws an InvalidArgumentException if other types of arguments are passed
    */
    public function testSetExpiryDate()
    {
        $obj = $this->obj;
        $dt = new DateTime('2015-01-01 00:00:00');

        $ret = $obj->set_expiry_date('2015-01-01 00:00:00');
        $this->assertSame($ret, $obj);
        $this->assertEquals($dt, $obj->expiry_date());

        $obj->set_expiry_date($dt);
        $this->assertEquals($dt, $obj->expiry_date());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_expiry_date(false);
    }

    /**
    * Assert that the `set_expiry_date` method:
    * - throws an InvalidArgumentException if a non-ts string value is passed
    */
    public function testSetExpiryDateBogusStringThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $obj = $this->obj;
        $obj->set_expiry_date('foobar');
    }

    /**
    * @dataProvider providerPublishStatus
    */
    public function testSetPublishStatus($publish_date, $expiry_date, $expected_status)
    {
        $obj = $this->obj;
        if ($publish_date !== null) {
            $obj->set_publish_date($publish_date);
        }
        if ($expiry_date !== null) {
            $obj->set_expiry_date($expiry_date);
        }
        $this->assertEquals($expected_status, $obj->publish_status());
    }

    public function providerPublishStatus()
    {
        return [
            [null, null, 'published'],
            ['yesterday', 'tomorrow', 'published'],
            ['2 days ago', 'yesterday', 'expired'],
            ['tomorrow', '+1 week', 'upcoming'],
            ['tomorrow', null, 'upcoming'],
            [null, 'tomorrow', 'published'],
            [null, 'yesterday', 'expired']
        ];
    }
}

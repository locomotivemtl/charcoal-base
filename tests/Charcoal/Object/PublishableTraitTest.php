<?php

namespace Charcoal\Tests\Object;

use \DateTime;

use \Charcoal\Object\PublishableInterface;
use \Charcoal\Object\PublishableTrait;
use \Charcoal\Object\ExpirableInterface;
use \Charcoal\Object\ExpirableTrait;

/**
 *
 */
abstract class PublicationClass implements
    ExpirableInterface,
    PublishableInterface
{
    use ExpirableTrait;
    use PublishableTrait;
}

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
        $this->obj = $this->getMockForAbstractClass('\Charcoal\Tests\Object\PublicationClass');
    }

    /**
     * Assert that the `setPublishedOn` method:
     * - is chainable
     * - sets the publishedOn value when a string is passed
     * - sets the publishedOn value when a DateTime is passed
     * - throws an InvalidArgumentException if other types of arguments are passed
     */
    public function testSetPublishedOn()
    {
        $obj = $this->obj;
        $dt = new DateTime('2015-01-01 00:00:00');

        $ret = $obj->setPublishedOn('2015-01-01 00:00:00');
        $this->assertSame($ret, $obj);
        $this->assertEquals($dt, $obj->publishedOn());

        $obj->setPublishedOn($dt);
        $this->assertEquals($dt, $obj->publishedOn());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setPublishedOn('foobar');
    }

    public function testSetPublicationStatus()
    {
        $obj = $this->obj;

        $obj->setPublicationStatus('draft');
        $this->assertEquals('draft', $obj->publicationStatus());

        $obj->setPublicationStatus('pending');
        $this->assertEquals('pending', $obj->publicationStatus());

        $obj->setPublishedOn('yesterday');
        $obj->setPublicationStatus('published');
        $this->assertEquals('published', $obj->publicationStatus());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->setPublicationStatus('foobar');
    }

    /**
     * @dataProvider providerPublicationStatus
     */
    public function testPublicationStatusFromDates($publishedOn, $expiredOn, $expectedStatus)
    {
        $obj = $this->obj;

        if ($publishedOn !== null) {
            $obj->setPublishedOn($publishedOn);
        }

        if ($expiredOn !== null) {
            $obj->setExpiredOn($expiredOn);
        }

        $obj->setPublicationStatus('draft');
        $this->assertEquals('draft', $obj->publicationStatus());

        $obj->setPublicationStatus('pending');
        $this->assertEquals('pending', $obj->publicationStatus());

        $obj->setPublicationStatus('published');
        $this->assertEquals($expectedStatus, $obj->publicationStatus());
    }

    public function providerPublicationStatus()
    {
        return [
            [ null, null, 'draft' ],
            [ 'yesterday', 'tomorrow', 'published' ],
            [ '2 days ago', 'yesterday', 'expired' ],
            [ 'tomorrow', '+1 week', 'scheduled' ],
            [ 'tomorrow', null, 'scheduled' ],
            [ null, 'tomorrow', 'draft' ],
            [ null, 'yesterday', 'expired' ]
        ];
    }

    public function testIsPublished()
    {
        $obj = $this->obj;
        $this->assertFalse($obj->isPublished());

        $obj->setPublishedOn('yesterday');

        $obj->setPublicationStatus('draft');
        $this->assertFalse($obj->isPublished());

        $obj->setPublicationStatus('published');
        $this->assertTrue($obj->isPublished());
    }
}

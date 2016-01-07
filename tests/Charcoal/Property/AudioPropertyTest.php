<?php

namespace Charcoal\Tests\Property;

use \Charcoal\Property\AudioProperty;

/**
 * ## TODOs
 * - 2015-03-12:
 */
class AudioPropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Hello world
     */
    public function testConstructor()
    {
        $obj = new AudioProperty();
        $this->assertInstanceOf('\Charcoal\Property\AudioProperty', $obj);

        $this->assertEquals(0, $obj->min_length());
        $this->assertEquals(0, $obj->max_length());
    }

    public function testType()
    {
        $obj = new AudioProperty();
        $this->assertEquals('audio', $obj->type());
    }

    public function testSetData()
    {
        $obj = new AudioProperty();
        $data = [
            'min_length'=>20,
            'max_length'=>500
        ];
        $ret = $obj->set_data($data);
        $this->assertSame($ret, $obj);

        $this->assertEquals(20, $obj->min_length());
        $this->assertEquals(500, $obj->max_length());
    }

    public function testSetMinLength()
    {
        $obj = new AudioProperty();

        $ret = $obj->set_min_length(5);
        $this->assertSame($ret, $obj);

        $this->assertEquals(5, $obj->min_length());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_min_length(false);
    }

    public function testSetMaxLength()
    {
        $obj = new AudioProperty();

        $ret = $obj->set_max_length(5);
        $this->assertSame($ret, $obj);

        $this->assertEquals(5, $obj->max_length());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_max_length(false);
    }

    /**
     * @dataProvider mimeExtensionProvider
     */
    public function testGenerateExtension($mime, $ext)
    {
        $obj = new AudioProperty();
        $obj->set_mimetype($mime);
        $this->assertEquals($ext, $obj->generate_extension());
    }

    public function mimeExtensionProvider()
    {
        return [
            ['audio/mpeg', 'mp3'],
            ['audio/wav', 'wav'],
            ['audio/x-wav', 'wav']
        ];
    }
}

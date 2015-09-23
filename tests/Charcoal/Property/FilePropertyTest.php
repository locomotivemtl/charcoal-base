<?php

namespace Charcoal\Tests\Property;

use \Charcoal\Property\FileProperty as FileProperty;

/**
* ## TODOs
* - 2015-03-12:
*/
class FilePropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $obj = new FileProperty();
        $this->assertInstanceOf('\Charcoal\Property\FileProperty', $obj);
        $this->assertEquals('uploads/', $obj->upload_path());
        $this->assertFalse($obj->overwrite());
        $this->assertEquals([], $obj->accepted_mimetypes());
        $this->assertEquals(134220000, $obj->max_filesize());
    }

    public function testType()
    {
        $obj = new FileProperty();
        $this->assertEquals('file', $obj->type());
    }

    public function testSetData()
    {
        $obj = new FileProperty();
        $ret = $obj->set_data(
            [
            'upload_path'=>'uploads/foobar/',
            'overwrite'=>true,
            'accepted_mimetypes'=>['image/x-foobar'],
            'max_filesize'=>(32*1024*1024)
            ]
        );
        $this->assertSame($ret, $obj);
    }

    public function testSetUploadPath()
    {
        $obj = new FileProperty();
        $ret = $obj->set_upload_path('foobar');
        $this->assertSame($ret, $obj);
        $this->assertEquals('foobar/', $obj->upload_path());

        $this->setExpectedException('\InvalidArgumentException');
        $obj->set_upload_path(42);
    }

    public function testSetOverwrite()
    {
        $obj = new FileProperty();
        $ret = $obj->set_overwrite(true);
        $this->assertSame($ret, $obj);
        $this->assertTrue($obj->overwrite());
    }

    public function testVaidationMethods()
    {
        $obj = new FileProperty();
        $ret = $obj->validation_methods();
        $this->assertContains('accepted_mimetypes', $ret);
        $this->assertContains('max_filesize', $ret);
    }

    public function testValidateAcceptedMimetypes()
    {
        $obj = new FileProperty();
        $obj->set_mimetype('image/x-foobar');
        $this->assertTrue($obj->validate_accepted_mimetypes());

        $this->assertEmpty($obj->accepted_mimetypes());
        $this->assertTrue($obj->validate_accepted_mimetypes());

        $obj->set_accepted_mimetypes(['image/x-barbaz']);
        $this->assertFalse($obj->validate_accepted_mimetypes());

        $obj->set_accepted_mimetypes(['image/x-foobar']);
        $this->assertTrue($obj->validate_accepted_mimetypes());
    }

    /**
    * @dataProvider filenameProvider
    */
    public function testSanitizeFilename($filename, $sanitized)
    {
        $obj = new FileProperty();
        $this->assertEquals($sanitized, $obj->sanitize_filename($filename));
    }

    public function filenameProvider()
    {
        return [
            ['foobar', 'foobar'],
            ['<foo/bar*baz?x:y|z>', '_foo_bar_baz_x_y_z_'],
            ['.htaccess', 'htaccess'],
            ['../../etc/passwd', '_.._etc_passwd']
        ];
    }

    // public function testGenerateFilenameWithoutIdentThrowsException()
    // {
    //     $obj = new FileProperty();
    //     $this->setExpectedException('\Exception');
    //     $obj->generate_filename();
    // }

    public function testGenerateFilename()
    {
        $obj = new FileProperty();
        $obj->set_ident('foo');
        $ret = $obj->generate_filename();
        //$this->assertContains('Foo', $ret);
        //$this->assertContains(date('Y-m-d H:i:s'), $ret);

        $obj->set_label('foobar');
        $ret = $obj->generate_filename();
        //$this->assertContains('foobar', $ret);
    }
}

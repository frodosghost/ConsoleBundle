<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Tests\Entity;

use Manhattan\Bundle\ConsoleBundle\Entity\Asset;

/**
 * AssetTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class AssetTest extends \PHPUnit_Framework_TestCase
{
    private $_asset = null;

    public function setUp()
    {
        $stub = $this->getMockForAbstractClass('Manhattan\Bundle\ConsoleBundle\Entity\Asset');
        $this->_asset = $stub;
    }

    public function tearDown()
    {
        $this->_asset = null;
    }

    public function testHasAsset()
    {
        // Test returns false when no data exists in the object
        $this->assertFalse($this->_asset->hasAsset(),
            ' ->hasAsset() returns false when no data has been set.');

        // Insert a filename to help the function check the data
        $this->_asset->setFilename('foo');

        $this->assertTrue($this->_asset->hasAsset(),
            '->hasAsset() returns true when the filename has been set.');
    }

    public function testGetAbsolutePath()
    {
        $this->_asset->expects($this->any())
            ->method('getUploadDir')
            ->will($this->returnValue('bar'));

        $this->_asset->setFilename('foo');

        // Setup a realpath because of the __DIR__ used in the absolute path function
        $test_path = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR.'Entity';

        $this->assertEquals($test_path. '/../../../../web/bar/foo', $this->_asset->getAbsolutePath(),
            '->getAbsolutePath() returns the correct path when data is set.');
    }

    public function testGetWebPath()
    {
        $this->_asset->expects($this->any())
            ->method('getUploadDir')
            ->will($this->returnValue('foo'));

        $this->_asset->setFilename('bar');

        $this->assertEquals('foo/bar', $this->_asset->getWebPath(),
            '->getWebPath() returns the correct path when data is set.');
    }

    public function testPreUpload()
    {
        // Setup mock class for testing upload file
        $mock_file = $this->getMockBuilder('\Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        $mock_file->expects($this->any())
            ->method('getMimetype')
            ->will($this->returnValue('foo'));
        $mock_file->expects($this->any())
            ->method('getClientOriginalName')
            ->will($this->returnValue('bar.foo'));

        // Sets the mock class and initiates the preupload function
        $this->_asset->setFile($mock_file);
        $this->_asset->preUpload();

        $this->assertEquals('foo', $this->_asset->getMimeType(),
            '->preUpload() correctly adds the mime_type when preparing file object.');

        $this->assertEquals('bar.foo', $this->_asset->getFilename(),
            '->preUpload() correctly adds the filename when preparing file object.');
    }

    public function testUpload()
    {
        // Setup mock class for testing upload file
        $mock_file = $this->getMockBuilder('\Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        // Sets the mock class and initiates the preupload function
        $this->_asset->setFile($mock_file);
        $this->_asset->upload();

        $this->assertEquals(NULL, $this->_asset->getFile(),
            '->upload() removes the file variable from the object.');
    }


    public function testUploadException()
    {
        // Setup mock class for testing upload file
        $mock_file = $this->getMockBuilder('\Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        // Sets exception to be sent and caught
        $mock_file->expects($this->any())
             ->method('move')
             ->will($this->throwException(new \Symfony\Component\HttpFoundation\File\Exception\UploadException));

        // Sets the mock class and initiates the preupload function
        $this->_asset->setFile($mock_file);

        $this->setExpectedException('Symfony\Component\HttpFoundation\File\Exception\UploadException');
        $this->_asset->upload();
    }

    /**
     * Sanitise filename to ensure correct name is generated
     */
    public function testSanitise()
    {
        $this->assertEquals('foo-bar.png', $this->_asset->sanitise('Foo Bar.png'));
        $this->assertEquals('foo-bar.png', $this->_asset->sanitise('Foo     Bar.png'));
        $this->assertEquals('foo-bar.png', $this->_asset->sanitise('Foo%&$Bar.png'));
        $this->assertEquals('-foo-bar.png', $this->_asset->sanitise('   Foo Bar.png'));
        $this->assertEquals('-phpfoo-bar.png', $this->_asset->sanitise('#<?phpFoo Bar.png'));
        $this->assertEquals('foo-bar-2343.png', $this->_asset->sanitise('Foo     Bar 2343.png'));
    }

    /**
     * Tests the extension as returned from the Filename
     */
    public function testGetExtension()
    {
        $this->_asset->setFilename('foo-bar-239-.jpg');

        $this->assertEquals('jpg', $this->_asset->getExtension(),
            '->getExtension() returns the correct file extension');

        $this->_asset->setFilename('foo-bar-239-.file.png');

        $this->assertEquals('png', $this->_asset->getExtension(),
            '->getExtension() returns the correct file extension');

        $this->_asset->setFilename('foo-bar-239-.');

        $this->assertEquals('', $this->_asset->getExtension(),
            '->getExtension() returns the correct file extension');

        $this->_asset->setFilename('');

        $this->assertEquals('', $this->_asset->getExtension(),
            '->getExtension() returns the correct file extension');

        $this->_asset->setFilename(NULL);

        $this->assertEquals('', $this->_asset->getExtension(),
            '->getExtension() returns the correct file extension');
    }

}

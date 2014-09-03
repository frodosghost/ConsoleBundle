<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Tests\Twig;

use Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension;

/**
 * ConsoleTwigExtensionTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class ConsoleTwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $twigEnvironment = null;

    public function setUp()
    {
        $this->twigEnvironment = $this->getMock('\Twig_Environment');
    }

    public function tearDown()
    {
        $this->_asset = null;
    }

    /**
     * Test Constructor
     *
     * @test
     * @covers Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension::__construct()
     */
    public function testConstructor()
    {
        $extension = new ConsoleTwigExtension($this->twigEnvironment, array('foo' => 'bar'), 'foo');

        $this->assertInstanceOf('Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension', $extension, 'Constructor function fails when correct elements are passed.');
    }

    /**
     * Test ->getName()
     *
     * @test
     * @covers Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension::getName()
     */
    public function testGetName()
    {
        $extension = new ConsoleTwigExtension($this->twigEnvironment, array('foo' => 'bar'), 'foo');

        $this->assertEquals('manhattan_console_twig', $extension->getName());
    }

    /**
     * Test ->getFunctions()
     *
     * @test
     * @covers Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension::getFunctions()
     */
    public function testGetFunctions()
    {
        $extension = new ConsoleTwigExtension($this->twigEnvironment, array('foo' => 'bar'), 'foo');
        $functions = $extension->getFunctions();

        $this->assertCount(1, $functions);
        $this->assertInstanceOf('\Twig_Function_Method', $functions['topHeader']);
    }

    /**
     * Test ->getFilters()
     *
     * @test
     * @covers Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension::getFilters()
     */
    public function testGetFilters()
    {
        $extension = new ConsoleTwigExtension($this->twigEnvironment, array('foo' => 'bar'), 'foo');
        $filters = $extension->getFilters();

        $this->assertCount(1, $filters);
        $this->assertInstanceOf('\Twig_SimpleFilter', $filters[0]);
    }

    /**
     * Test ->getTemplate()
     *
     * @test
     * @covers Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension::getTemplate()
     */
    public function testGetTemplate()
    {
        $mockTemplate = $this->getMockBuilder('\Twig_Template')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->twigEnvironment->expects($this->once())
            ->method('loadTemplate')
            ->with('foo')
            ->will($this->returnValue($mockTemplate));

        $extension = new ConsoleTwigExtension($this->twigEnvironment, array('foo' => 'bar'), 'foo');

        $this->assertInstanceOf('\Twig_Template', $extension->getTemplate(), '->getTemplate() does not return instance of Twig Template');
        $this->assertEquals($mockTemplate, $extension->getTemplate(), '->getTemplate() does not return same class passed in ->expects()');
    }

    /**
     * Test ->topHeader()
     *
     * @test
     * @covers Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension::topHeader()
     */
    public function testTopHeader()
    {
        $mockTemplate = $this->getMockBuilder('\Twig_Template')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $mockTemplate->expects($this->any())
            ->method('renderBlock')
            ->with('topHeader');

        $this->twigEnvironment->expects($this->once())
            ->method('loadTemplate')
            ->with('foo')
            ->will($this->returnValue($mockTemplate));

        $extension = new ConsoleTwigExtension($this->twigEnvironment, array('foo' => 'bar'), 'foo');

        $this->assertEquals('', $extension->topHeader(), '->topHeader() does not return html as expected');
    }

    /**
     * Test ->slugify()
     *
     * @test
     * @covers Manhattan\Bundle\ConsoleBundle\Twig\ConsoleTwigExtension::slugify()
     */
    public function testSlugify()
    {
        $extension = new ConsoleTwigExtension($this->twigEnvironment, array('foo' => 'bar'), 'foo');

        $this->assertEquals('hello-world', $extension->slugify('Hello World'));
        $this->assertEquals('hello-world', $extension->slugify('Hello world'));
        $this->assertEquals('hello_world', $extension->slugify('Hello world', '_'));
        $this->assertEquals('hello_world', $extension->slugify('HeLLo worLd', '_'));
        $this->assertEquals('hello-world', $extension->slugify('HeLLo   worLd'));
        $this->assertEquals('hell0-wor33', $extension->slugify('HeLL0 wor33'));
        $this->assertEquals('h-llo-wor-ld', $extension->slugify('H*llo wor$%ld'));
        $this->assertEquals('hello-world', $extension->slugify(' Hello World '));
    }

}

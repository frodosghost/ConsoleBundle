<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Tests\Entity\User;

use Manhattan\Bundle\ConsoleBundle\Entity\User\SocialAccount;

/**
 * SocialAccountTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class SocialAccountTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Testing ->getProvider()
     *
     * @dataProvider accountProvider
     */
    public function testGetProvider($outlet, $expected)
    {
        $socialAccount = new SocialAccount();

        $socialAccount->setOutlet($outlet);
        $this->assertEquals($expected, $socialAccount->getProvider(), sprintf('->getProvider() returns "%s" in a string when Outlet is set to "%s"', $expected, $outlet));
    }

    public function accountProvider()
    {
        return array(
            array('', ''),
            array('foo', ''),
            array('google-plus', 'Google Plus'),
            array('twitter', 'Twitter'),
            array('linkedin', 'LinkedIn')
        );
    }

    /**
     * Testing ->__toString()
     *
     * @dataProvider urlProvider
     */
    public function testToString($outlet, $identifier, $expected)
    {
        $socialAccount = new SocialAccount();

        $socialAccount->setOutlet($outlet);
        $socialAccount->setIdentifier($identifier);
        $this->assertEquals($expected, $socialAccount->__toString(), sprintf('->__toString() returns "%s" in a string when Outlet is set to "%s" and Identifer is set to "%s"', $expected, $outlet, $identifier));
    }

    public function urlProvider()
    {
        return array(
            array('', '', ''),
            array('foo', 'bar', ''),
            array('google-plus', 'foo', 'https://plus.google.com/foo/posts'),
            array('twitter', 'foo', 'http://twitter.com/foo'),
            array('linkedin', 'foo', 'http://www.linkedin.com/in/foo/')
        );
    }

    /**
     * Testing ->hasProvider()
     *
     * @dataProvider hasProvider
     */
    public function testHasProvider($outlet, $provider, $expected)
    {
        $socialAccount = new SocialAccount();

        $socialAccount->setOutlet($outlet);
        $this->assertEquals($expected, $socialAccount->hasProvider($provider), sprintf('->hasProvider() returns "%s" when Outlet is set to "%s" and Identifer is set to "%s"', $expected, $outlet, $provider));
    }

    public function hasProvider()
    {
        return array(
            array('', '', TRUE),
            array('foo', 'bar', FALSE),
            array('google-plus', 'google-plus', TRUE),
            array('twitter', 'twitter', TRUE),
            array('linkedin', 'foo', FALSE)
        );
    }

}

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

use Manhattan\Bundle\ConsoleBundle\Entity\User;

/**
 * UserTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test onCreate Behaviour
     */
    public function testOnCreate()
    {
        $user = new User();

        $this->assertNull($user->getUpdatedAt(), '->getUpdatedAt() returns NULL is the onCreate has not been called.');
        $this->assertNull($user->getCreatedAt(), '->getCreatedAt() returns NULL is the onCreate has not been called.');

        $user->onCreate();

        $this->assertInstanceOf('\DateTime', $user->getUpdatedAt(), '->getUpdatedAt() returns DateTime instance when onCreate is called.');
        $this->assertInstanceOf('\DateTime', $user->getCreatedAt(), '->getCreatedAt() returns DateTime instance when onCreate is called.');
    }

    /**
     * Test onUpdate Behaviour
     */
    public function testOnUpdate()
    {
        $user = new User();

        $this->assertNull($user->getUpdatedAt(), '->getUpdatedAt() returns NULL when onUpdate has not been called.');
        $this->assertNull($user->getCreatedAt(), '->getCreatedAt() returns NULL when onUpdate has not been called.');

        $user->onUpdate();

        $this->assertInstanceOf('\DateTime', $user->getUpdatedAt(), '->getUpdatedAt() returns DateTime instance when onUpdate is called.');
        $this->assertNull($user->getCreatedAt(), '->getCreatedAt() has not been updated when onUpdate is called.');
    }

    /**
     * Testing ->getGravatar()
     */
    public function testGetGravatar()
    {
        $user = new User();

        $this->assertEquals('http://www.gravatar.com/avatar/d41d8cd98f00b204e9800998ecf8427e?s=80&d=mm&r=g', $user->getGravatar(), '->getGravatar() returns correct string when the Email is empty');

        $user->setEmail('foo@bar.com');

        /**
         * Email set and Image Quality variable
         */
        $this->assertEquals('http://www.gravatar.com/avatar/f3ada405ce890b6f8204094deb12d8a8?s=80&d=mm&r=g', $user->getGravatar(), '->getGravatar() returns correct string when the Email is set to "foo@bar.com"');
        $this->assertEquals('http://www.gravatar.com/avatar/f3ada405ce890b6f8204094deb12d8a8?s=50&d=mm&r=g', $user->getGravatar(50), '->getGravatar() returns correct string imageSize variable is set');

        /**
         * Default Image Variable
         */
        $this->assertEquals('http://www.gravatar.com/avatar/f3ada405ce890b6f8204094deb12d8a8?s=80&d=404&r=g', $user->getGravatar(80, '404'), '->getGravatar() returns correct string when the Default Image variable is set');
        $this->assertEquals('http://www.gravatar.com/avatar/f3ada405ce890b6f8204094deb12d8a8?s=80&d=mm&r=g', $user->getGravatar(80, 'foo'), '->getGravatar() returns correct string when the Default Image variable is set, but is not an option');

        /**
         * Rating Variable
         */
        $this->assertEquals('http://www.gravatar.com/avatar/f3ada405ce890b6f8204094deb12d8a8?s=80&d=mm&r=pg', $user->getGravatar(80, null, 'pg'), '->getGravatar() returns correct string when the Rating variable is set');
        $this->assertEquals('http://www.gravatar.com/avatar/f3ada405ce890b6f8204094deb12d8a8?s=80&d=mm&r=g', $user->getGravatar(80, null, 'foo'), '->getGravatar() returns correct string when the Rating variable is set, but is not an option');
    }

}

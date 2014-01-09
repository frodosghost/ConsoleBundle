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

}

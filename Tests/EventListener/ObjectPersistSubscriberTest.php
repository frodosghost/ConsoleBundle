<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Tests\EventListener;

use Manhattan\Bundle\ConsoleBundle\EventListener\ObjectPersistSubscriber;

/**
 * ObjectPersistSubscriberTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class ObjectPersistSubscriberTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test Constructor
     */
    public function testConstructor()
    {
        $mock_interface = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $ops = new ObjectPersistSubscriber($mock_interface);

        $this->assertInstanceOf('Manhattan\Bundle\ConsoleBundle\EventListener\ObjectPersistSubscriber', $ops, 'Construct function works to setup object.');
    }

}


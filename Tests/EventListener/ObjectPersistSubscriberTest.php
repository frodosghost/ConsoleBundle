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

    public function testPrePersist()
    {
        $mockSecurity = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();

        $mock_interface = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $mock_interface->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockSecurity));

        $mockEventArgs = $this->getMockBuilder('Doctrine\Common\Persistence\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPublish = $this->getMockForAbstractClass('Manhattan\Bundle\ConsoleBundle\Entity\Publish');

        $mockEventArgs->expects($this->once())
            ->method('getEntity')
            ->will($this->returnValue($mockPublish));

        $ops = new ObjectPersistSubscriber($mock_interface);

        $ops->prePersist($mockEventArgs);
    }

    public function testPrePersistWithToken()
    {

        $mockSecurity = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $mockSecurity->expects($this->atLeastOnce())
            ->method('getToken')
            ->will($this->returnValue($mockToken = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')));

        $mock_interface = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $mock_interface->expects($this->atLeastOnce())
            ->method('get')
            ->with('security.context')
            ->will($this->returnValue($mockSecurity));

        $mockEventArgs = $this->getMockBuilder('Doctrine\Common\Persistence\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPublish = $this->getMockForAbstractClass('Manhattan\Bundle\ConsoleBundle\Entity\Publish');

        $mockEventArgs->expects($this->once())
            ->method('getEntity')
            ->will($this->returnValue($mockPublish));

        $ops = new ObjectPersistSubscriber($mock_interface);

        $ops->prePersist($mockEventArgs);
    }

}


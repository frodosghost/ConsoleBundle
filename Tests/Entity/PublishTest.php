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

use Manhattan\Bundle\ConsoleBundle\Entity\Publish;

/**
 * PublishTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class PublishTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setCreatedBy Behaviour
     */
    public function testCreatedBy()
    {
        $publish = $this->getMockForAbstractClass('Manhattan\Bundle\ConsoleBundle\Entity\Publish');
        $mock_user = $this->getMock('Manhattan\Bundle\ConsoleBundle\Entity\User');

        $this->assertInstanceOf('Manhattan\Bundle\ConsoleBundle\Entity\Publish', $publish->setCreatedBy($mock_user), '->setCreatedBy() returns instance of class when set with User');
    }

    /**
     * Test setUpdatedBy Behaviour
     */
    public function testUpdatedBy()
    {
        $publish = $this->getMockForAbstractClass('Manhattan\Bundle\ConsoleBundle\Entity\Publish');
        $mock_user = $this->getMock('Manhattan\Bundle\ConsoleBundle\Entity\User');

        $this->assertInstanceOf('Manhattan\Bundle\ConsoleBundle\Entity\Publish', $publish->setUpdatedBy($mock_user), '->setUpdatedBy() returns instance of class when set with User');
    }

}

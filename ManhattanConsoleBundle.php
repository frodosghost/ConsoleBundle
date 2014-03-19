<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Manhattan\Bundle\ConsoleBundle\DependencyInjection\Compiler\AccessControlPass;

class ManhattanConsoleBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AccessControlPass());
    }
}

<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AccessControlPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('security.access_map')) {
            return;
        }

        $accessMapDefinition = $container->getDefinition('security.access_map');
        $accessMapDefinition
            ->addMethodCall(
                'add', array(
                    new Reference('manhattan.console.subdomain_request_matcher'),
                    array('ROLE_ADMIN')
                )
            );
    }
}

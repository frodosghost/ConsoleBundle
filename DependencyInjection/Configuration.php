<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('manhattan_console');

        $rootNode
            ->children()
                ->scalarNode('domain')
                    ->isRequired()->cannotBeEmpty()
                    ->defaultValue('')
                    ->info('Sets the domain. For example "domain.com" or "domain.dev".')
                    ->end()
                ->arrayNode('users')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('from')
                            ->defaultValue('')
                            ->info('Sets the From address when a Console email is sent')
                            ->end()
                        ->scalarNode('subject')
                            ->defaultValue('Console')
                            ->info('Sets the Subject when a User email is sent')
                            ->end()
                        ->scalarNode('console_name')
                            ->defaultValue('Manhattan')
                            ->info('Sets the Console name as sent in emails')
                            ->end()
                        ->arrayNode('templates')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('setpassword_txt')
                                    ->defaultValue('ManhattanConsoleBundle:Email:setpassword.txt.twig')
                                    ->info('Sets base template for sending an email.')
                                    ->end()
                                ->scalarNode('setpassword_html')
                                    ->defaultValue('ManhattanConsoleBundle:Email:setpassword.html.twig')
                                    ->info('Sets base template for sending an email.')
                                    ->end()
                                ->scalarNode('resetting_email')
                                    ->defaultValue('ManhattanConsoleBundle:Email:reset_email.html.twig')
                                    ->info('Sets base template for resetting FOS User password.')
                                    ->end()
                            ->end()
                        ->end()
                        ->scalarNode('user_class')
                            ->defaultValue('Manhattan\Bundle\ConsoleBundle\Entity\User')
                            ->info('Sets the User Class to use with FOSUserBundle.')
                            ->end()
                    ->end()
                ->end()
                ->arrayNode('navigation')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('title')
                            ->defaultValue('Console')
                            ->info('Title as appears in the main navigation header')
                            ->end()
                        ->scalarNode('link')
                            ->defaultValue('console_index')
                            ->info('Link as set in the main navigation header')
                            ->end()
                    ->end()
                ->end()
                ->arrayNode('user_roles')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('role')->end()
                            ->scalarNode('name')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

}

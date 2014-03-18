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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ManhattanConsoleExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('domain', $config['domain']);
        $container->setParameter('console.users', $config['users']);
        $container->setParameter('console.users.from', $config['users']['from']);

        $container->setParameter('console.users.user_class', $config['users']['user_class']);
        $container->setParameter('console.users.console_name', $config['users']['console_name']);

        $container->setParameter('console.email.registration.subject', $config['email']['registration']['subject']);
        $container->setParameter('console.email.registration.template', $config['email']['registration']['template']);

        $container->setParameter('console.email.resetting.subject', $config['email']['resetting']['subject']);
        $container->setParameter('console.email.resetting.template', $config['email']['resetting']['template']);

        // Navigation Bar Configuration Values
        $container->setParameter('console.navigation.title', $config['navigation']['title']);
        $container->setParameter('console.navigation.link', $config['navigation']['link']);

        // User Roles
        $this->remapUserRoles($config['user_roles'], $container);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * Remaps parsed array for default into Choices field
     *
     * @param  array            $config
     * @param  ContainerBuilder $container
     */
    protected function remapUserRoles(array $config, ContainerBuilder $container)
    {
        $userRoles = array();

        foreach ($config as $role) {
            $userRoles[$role['role']] = $role['name'];
        }

        $container->setParameter('manhattan.console.user.roles', $userRoles);
    }

    /**
     * Prepend form field settings for TwigBundle
     *
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        // determine if TwigBundle is registered
        if (isset($bundles['TwigBundle'])) {

            $config = array('form' => array('resources' => array (
                'ManhattanConsoleBundle:Form:fields.html.twig',
                'OrnjMarkdownBundle:Form:fields.html.twig'
            )));

            foreach ($container->getExtensions() as $name => $extension) {
                switch ($name) {
                case 'twig':
                    $container->prependExtensionConfig($name, $config);
                    break;
                }
            }
        }

        // Once Manhattan Console is registered include the additional configuration files
        if (isset($bundles['ManhattanConsoleBundle'])) {
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('config.yml');
        }
    }

}

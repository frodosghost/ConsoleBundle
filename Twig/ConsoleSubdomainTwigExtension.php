<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Twig;

use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;

/**
 * Twig Extension for handling Subdomains
 *
 * @author James Rickard <james@frodosghost.com>
 */
class ConsoleSubdomainTwigExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $siteManager;

    /**
     * @var string
     */
    private $domain;

    /**
     * Constructor
     *
     * @param ContainerInterface $request
     * @param string             $domain
     */
    public function __construct(SiteManager $siteManager, $domain)
    {
        $this->siteManager = $siteManager;
        $this->domain = $domain;
    }

    public function getGlobals()
    {
        return array(
            'subdomain' => $this->siteManager->getSubdomain()
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manhattan_console_subdomain_extension';
    }
}

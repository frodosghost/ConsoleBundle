<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;
use Doctrine\ORM\EntityManager;

class SubdomainListener
{
    private $siteManager;

    private $em;

    private $domain;

    public function __construct(SiteManager $siteManager, EntityManager $em, $domain)
    {
        $this->siteManager = $siteManager;
        $this->em = $em;
        $this->domain = $domain;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (preg_match('/^(console)./', $request->getHttpHost())) {
            $this->siteManager->setSubdomain('console');
        }
    }
}

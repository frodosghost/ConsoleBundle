<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Security;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;
use Symfony\Component\HttpFoundation\Request;

class ConsoleSubDomainRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var Manhattan\Bundle\ConsoleBundle\Site\SiteManager
     */
    private $siteManager;

    /**
     * @var string
     */
    private $domain;

    public function __construct(SiteManager $siteManager, $domain)
    {
        $this->siteManager = $siteManager;
        $this->domain = $domain;
    }

    public function matches(Request $request)
    {
        if (preg_match('/^(console)./', $request->server->get('HTTP_HOST'))) {
            $this->siteManager->setSubdomain('console');
            return true;
        }

        return false;
    }
}

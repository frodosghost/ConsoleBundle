<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Site;

class SiteManager
{
    private $site;

    private $subdomain;

    public function getSite()
    {
        return $this->site;
    }

    public function setSite($site)
    {
        $this->site = $site;
    }

    public function getSubdomain()
    {
        return $this->subdomain;
    }

    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
    }
}

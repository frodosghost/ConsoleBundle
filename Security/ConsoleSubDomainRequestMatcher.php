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
use Symfony\Component\HttpFoundation\Request;

class ConsoleSubDomainRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request)
    {
        return preg_match('/^(console)./', $request->server->get('HTTP_HOST'));
    }
}

<?php

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

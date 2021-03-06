<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ConfigureMenuEvent extends Event
{
    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @param Knp\Menu\FactoryInterface $factory
     */
    private $factory;

    /**
     * @param Knp\Menu\ItemInterface
     */
    private $menu;

    /**
     * @param Knp\Menu\ItemInterface $securityContext
     */
    private $securityContext;

    /**
     * @param Knp\Menu\ItemInterface $securityContext
     */
    private $token;

    /**
     * @var Manhattan\Bundle\ConsoleBundle\Site\SiteManager
     */
    private $siteManager;

    /**
     * @param Request                $request
     * @param FactoryInterface       $factory
     * @param ItemInterface          $menu
     * @param AuthorizationChecker   $securityContext
     * @param TokenStorage           $token
     * @param SiteManager            $siteManager
     */
    public function __construct(Request $request, FactoryInterface $factory, ItemInterface $menu, AuthorizationChecker $securityContext, TokenStorage $token, SiteManager $siteManager)
    {
        $this->request = $request;
        $this->factory = $factory;
        $this->menu = $menu;
        $this->securityContext = $securityContext;
        $this->token = $token;
        $this->siteManager = $siteManager;
    }

     /**
      * @return Knp\Menu\FactoryInterface
      */
     public function getFactory()
     {
         return $this->factory;
     }

    /**
     * @return Knp\Menu\ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return Symfony\Component\Security\Core\SecurityContextInterface
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * @return Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Manhattan\Bundle\ConsoleBundle\Site\SiteManager
     */
    public function getSiteManager()
    {
        return $this->siteManager;
    }

    /**
     * Lazy Loading of security context.
     * Returns TokenInterface
     *
     * @link(Circular Reference when injecting Security Context, http://stackoverflow.com/a/8713339/174148)
     * @return TokenInterface
     */
    public function getSecurityToken()
    {
        return $this->token;
    }
}

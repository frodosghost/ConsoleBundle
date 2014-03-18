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
use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ConfigureMenuEvent extends Event
{
    /**
     * @param Knp\Menu\FactoryInterface $factory
     */
    private $factory;

    /**
     * @param Knp\Menu\ItemInterface $menu
     */
    private $menu;

    /**
     * @param Knp\Menu\ItemInterface $securityContext
     */
    private $securityContext;

    /**
     * @var Manhattan\Bundle\ConsoleBundle\Site\SiteManager
     */
    private $siteManager;

    /**
     * @param FactoryInterface         $factory
     * @param ItemInterface            $menu
     * @param SecurityContextInterface $securityContext
     * @param SiteManager              $siteManager
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu, SecurityContextInterface $securityContext, SiteManager $siteManager)
    {
        $this->factory = $factory;
        $this->menu = $menu;
        $this->securityContext = $securityContext;
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
     * @return Manhattan\Bundle\ConsoleBundle\Site\SiteManager
     */
    public function getSiteManager()
    {
        return $this->siteManager;
    }
}

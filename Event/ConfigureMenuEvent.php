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
     * @param Knp\Menu\ItemInterface $security_context
     */
    private $security_context;

    /**
     * @param Knp\Menu\FactoryInterface $factory
     * @param Knp\Menu\ItemInterface $menu
     * @param Symfony\Component\Security\Core\SecurityContextInterface $security_context
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu, SecurityContextInterface $security_context)
    {
        $this->factory = $factory;
        $this->menu = $menu;
        $this->security_context = $security_context;
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
        return $this->security_context;
    }
}

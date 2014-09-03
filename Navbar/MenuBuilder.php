<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Navbar;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Manhattan\Bundle\ConsoleBundle\Event\MenuEvents;
use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;
use Manhattan\Bundle\ConsoleBundle\Event\ConfigureMenuEvent;

/**
 * Manhattan Console Navigation Bar Menu Builder
 */
class MenuBuilder
{
    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var Knp\Menu\FactoryInterface
     */
    protected $factory;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var Manhattan\Bundle\ConsoleBundle\Site\SiteManager
     */
    private $siteManager;

    /**
     * @var Boolean
     */
    private $is_logged_in = false;

    /**
     * @var Boolean
     */
    private $is_super_admin = false;

    /**
     * Constructor
     *
     * @param Knp\Menu\FactoryInterface $factory
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    public function __construct(Request $request, FactoryInterface $factory, EventDispatcherInterface $eventDispatcher, SecurityContextInterface $securityContext, SiteManager $siteManager)
    {
        $this->request = $request;
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->securityContext = $securityContext;
        $this->siteManager = $siteManager;

        $this->is_logged_in = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
        $this->is_super_admin = $this->securityContext->isGranted('ROLE_SUPER_ADMIN');
    }

    /**
     * @return Knp\Menu\FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
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

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'navbar' => true
        ));

        if ($this->is_logged_in) {
            $this->getEventDispatcher()->dispatch(MenuEvents::CONFIGURE, new ConfigureMenuEvent(
                $this->getRequest(),
                $this->getFactory(),
                $menu,
                $this->getSecurityContext(),
                $this->getSiteManager()
            ));
        }

        return $menu;
    }

    public function createRightSideMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'navbar' => true,
            'pull-right' => true
        ));

        if ($this->is_super_admin) {
            $users = $menu->addChild('Users', array(
                'route'=>'console_users',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain()),
                'dropdown' => true,
                'caret' => true
            ));

            $users->addChild('List Users', array(
                'route' => 'console_users',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain())
            ));
            $users->addChild('Add User', array(
                'route' => 'console_users_new',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain())
            ));
        }

        if ($this->is_logged_in) {
            $profile = $menu->addChild('Profile', array(
                'route'=>'fos_user_profile_show',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain()),
                'dropdown' => true,
                'caret' => true
            ));

            $profile->addChild('Show Profile', array(
                'route' => 'fos_user_profile_show',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain())
            ));
            $profile->addChild('Edit Profile', array(
                'route' => 'fos_user_profile_edit',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain())
            ));
            $profile->addChild('Change Password', array(
                'route' => 'fos_user_change_password',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain())
            ));
            // /$this->addDivider($profile);
            $profile->addChild('Logout', array(
                'route' => 'fos_user_security_logout',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain())
            ));
        } else {
            $menu->addChild('Login', array(
                'route' => 'fos_user_security_login',
                'routeParameters' => array('subdomain' => $this->getSiteManager()->getSubdomain())
            ));
        }

        return $menu;
    }
}

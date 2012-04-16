<?php
namespace Manhattan\Bundle\ConsoleBundle\Navbar;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;

/**
 * Manhattan Console Navigation Bar Menu Builder
 *
 */
class MenuBuilder extends AbstractNavbarMenuBuilder
{
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getRequestUri());
        $menu->setChildrenAttribute('class', 'nav');
        //$menu->addChild('Layout', array('route' => 'console_index'));

        return $menu;
    }

    public function createRightSideMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getRequestUri());
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $menu->addChild('Logout', array('route' => '_logout'));

        return $menu;
    }
}

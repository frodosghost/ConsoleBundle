<?php
namespace Manhattan\Bundle\ConsoleBundle\Navbar;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;

use Manhattan\Bundle\ConsoleBundle\Event\MenuEvents;
use Manhattan\Bundle\ConsoleBundle\Event\ConfigureMenuEvent;

/**
 * Manhattan Console Navigation Bar Menu Builder
 *
 */
class MenuBuilder extends AbstractNavbarMenuBuilder
{
    /**
     * @var Knp\Menu\FactoryInterface
     */
    protected $factory;

    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $event_dispatcher;

    /**
     * @var Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $security_context;

    /**
     * @var Boolean
     */
    private $is_logged_in = false;

    /**
     * Constructor
     *
     * @param Knp\Menu\FactoryInterface $factory
     * @param Symfony\Component\EventDispatcher\EventDispatcher $event_dispatcher
     * @param Symfony\Component\Security\Core\SecurityContextInterface $security_context
     */
    public function __construct(FactoryInterface $factory, EventDispatcher $event_dispatcher, SecurityContextInterface $security_context)
    {
        parent::__construct($factory);

        $this->factory = $factory;
        $this->event_dispatcher = $event_dispatcher;
        $this->security_context = $security_context;

        //$this->is_logged_in = $this->security_context->isGranted('IS_AUTHENTICATED_FULLY');
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
        return $this->event_dispatcher;
    }

    /**
     * @return Symfony\Component\Security\Core\SecurityContextInterface
     */
    public function getSecurityContext()
    {
        return $this->security_context;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->getFactory()->createItem('root');
        //$menu->setCurrentUri($request->getRequestUri());
        $menu->setChildrenAttribute('class', 'nav');

        $this->getEventDispatcher()->dispatch(MenuEvents::CONFIGURE, new ConfigureMenuEvent($this->getFactory(), $menu, $this->getSecurityContext()));

        return $menu;
    }

    public function createRightSideMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        //$menu->setCurrentUri($request->getRequestUri());
        $menu->setChildrenAttribute('class', 'nav pull-right');

        if ($this->is_logged_in) {
            $menu->addChild('Logout', array('route' => '_logout'));
        } else {
            $menu->addChild('Login', array('route' => '_login'));
        }

        return $menu;
    }
}

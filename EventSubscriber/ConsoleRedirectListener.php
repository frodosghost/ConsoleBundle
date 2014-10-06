<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\EventSubscriber;

use FOS\UserBundle\Model\User;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConsoleRedirectListener implements EventSubscriberInterface
{
    private $router;

    private $siteManager;

    /**
     * @param Knp\Menu\ItemInterface $securityContext
     */
    private $securityContext;

    public function __construct(UrlGeneratorInterface $router, SiteManager $siteManager)
    {
        $this->router = $router;
        $this->siteManager = $siteManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'resetCompleted'
        );
    }

    public function resetCompleted(FormEvent $event)
    {
        $form = $event->getForm();
        $user = null;

        if ($form->getData() instanceof User) {
            $user = $form->getData();

            $event->setResponse(new RedirectResponse($this->router->generate('fos_user_profile_show', array(
                'subdomain' => $this->siteManager->getSubdomain()
            ))));
        }
    }

}

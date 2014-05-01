<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FOSUserListener implements EventSubscriberInterface
{
    private $router;
    private $tokenTtl;
    private $siteManager;

    public function __construct(UrlGeneratorInterface $router, SiteManager $siteManager, $tokenTtl)
    {
        $this->router = $router;
        $this->siteManager = $siteManager;
        $this->tokenTtl = $tokenTtl;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_RESET_INITIALIZE => 'onResettingResetInitialize',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResettingResetSuccess',
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordSuccess',
        );
    }

    public function onResettingResetInitialize(GetResponseUserEvent $event)
    {
        if (!$event->getUser()->isPasswordRequestNonExpired($this->tokenTtl)) {
            $event->setResponse(new RedirectResponse($this->router->generate('fos_user_resetting_request', array(
                'subdomain' => $this->siteManager->getSubdomain()
            ))));
        }
    }

    public function onResettingResetSuccess(FormEvent $event)
    {
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();

        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
        $user->setEnabled(true);
    }

    /**
     * Used on redirect when called in ChangePasswordController
     *
     * @param  FormEvent $event
     */
    public function onChangePasswordSuccess(FormEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->router->generate('fos_user_profile_show', array(
            'subdomain' => $this->siteManager->getSubdomain()
        ))));
    }
}

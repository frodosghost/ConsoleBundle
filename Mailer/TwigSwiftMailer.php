<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Mailer\TwigSwiftMailer as BaseTwigSwiftMailer;

/**
 * TwigSwiftMailer
 *
 * Provides email functionality
 */
class TwigSwiftMailer extends BaseTwigSwiftMailer
{

    /**
     * Sends an email when a User is created
     *
     * @param UserInterface $user
     */
    public function sendCreateUserEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('console_users_password_set', array(
            'token' => $user->getConfirmationToken()
        ), true);
        $indexUrl = $this->parameters['console_link'];
        $consoleIndex = $this->router->generate($indexUrl, array(), true);

        $context = array(
            'user' => $user,
            'url' => $url,
            'console_name' => $this->parameters['console_name'],
            'console_link' => $consoleIndex,
            'subject' => $this->parameters['subject']['confirmation']
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

}

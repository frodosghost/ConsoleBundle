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
use Manhattan\Bundle\ConsoleBundle\Site\SiteManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * TwigSwiftMailer
 *
 * Provides email functionality
 */
class TwigSwiftMailer
{
    private $mailer;
    private $router;
    private $twig;
    private $parameters;
    private $siteManager;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, SiteManager $siteManager, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
        $this->siteManager = $siteManager;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('fos_user_registration_confirm', array(
            'token' => $user->getConfirmationToken(),
            'subdomain' => $this->siteManager->getSubdomain()
        ), true);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['resetting'];
        $url = $this->router->generate('fos_user_resetting_reset', array(
            'token' => $user->getConfirmationToken(),
            'subdomain' => $this->siteManager->getSubdomain()
        ), true);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['resetting'], $user->getEmail());
    }

    /**
     * Sends an email when a User is created
     *
     * @param UserInterface $user
     */
    public function sendCreateUserEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('console_users_password_set', array(
            'token' => $user->getConfirmationToken(),
            'subdomain' => $this->siteManager->getSubdomain()
        ), true);
        $indexUrl = $this->parameters['console_link'];
        $consoleIndex = $this->router->generate($indexUrl, array(
            'subdomain' => $this->siteManager->getSubdomain()
        ), true);

        $context = array(
            'user' => $user,
            'url' => $url,
            'console_name' => $this->parameters['console_name'],
            'console_link' => $consoleIndex,
            'subject' => $this->parameters['subject']['confirmation']
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }

}

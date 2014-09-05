<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormError;

use Manhattan\Bundle\ConsoleBundle\Entity\User;
use Manhattan\Bundle\ConsoleBundle\Form\User\ProfileSocialType;

class ProfileSocialSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        // Checks if the Object exists
        if ($data->getId()) {
            $form->add('socialAccounts', 'collection', array(
                'label' => 'Social Accounts',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'type' => new ProfileSocialType(),
                'attr' => array(
                    'class' => 'details-collection social-accounts'
                ),
                'options' => array(
                    'horizontal' => true,
                    'label_render' => false,
                )
            ));
        }
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        /**
         * Checking Entity of Form is Item
         */
        if ($form->getData() instanceof User) {
            $identifer = $form->getData()->getId();

            if (isset($data['socialAccounts']) && count($data['socialAccounts']) > 0) {
                foreach ($data['socialAccounts'] as &$child) {
                    if (!empty($identifer)) {
                        $child['user'] = $identifer;
                    }
                }

                $event->setData($data);
            }
        }
    }

}

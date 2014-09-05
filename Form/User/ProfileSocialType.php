<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileSocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('outlet', 'choice', array(
                'label' => false,
                'choices' => array(
                    'googleplus' => 'Google Plus',
                    'linkedin' => 'LinkedIn',
                    'twitter' => 'Twitter',
                ),
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('identifier', null, array(
                'label' => false
            ))
            ->add('user', 'suggest_type', array(
                'class' => 'Manhattan\Bundle\ConsoleBundle\Entity\User',
                'label' => false
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manhattan\Bundle\ConsoleBundle\Entity\User\SocialAccount'
        ));
    }

    public function getName()
    {
        return 'user_social_account';
    }

}

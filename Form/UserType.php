<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Username',
                'help_block' => 'Username has no spaces. eg: johndoe'
            ))
            ->add('email', 'text', array(
                'label' => 'Email'
            ))
            ->add('enabled', 'choice', array(
                'choices' => array(
                    0 => 'Disabled',
                    1 => 'Enabled'
                )
            ))
            ->add('roles', 'user_roles', array(
                'expanded' => true,
                'multiple' => true
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manhattan\Bundle\ConsoleBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}

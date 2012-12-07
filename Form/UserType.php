<?php

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
                'label' => 'Username'
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
            ->add('roles', 'choice', array(
                'expanded' => true,
                'multiple' => true,
                'choices' => array(
                    'ROLE_ADMIN' => 'User',
                    'ROLE_SUPER_ADMIN' => 'Administrator'
                )
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

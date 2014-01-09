<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/*
 * This is the field display type that builds User Roles to display
 * on the creation page for a User.
 */
class UserRolesType extends AbstractType
{
    /**
     * Array of choices to use as User ROles
     *
     * @var array
     */
    private $userRoleChoices;

    public function __construct(array $userRoleChoices)
    {
        $this->userRoleChoices = $userRoleChoices;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->userRoleChoices,
            'attr' => array('class' => 'short')
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'user_roles';
    }
}

<?php
/*
 * This is the field display type that builds a form to display
 * publish states for database pages.
 */

namespace Manhattan\Bundle\ConsoleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PublishStateType extends AbstractType
{
    /**
     * Array of choices to use as Publish States
     *
     * @var array
     */
    private $publishStateChoices;

    public function __construct(array $publishStateChoices)
    {
        $this->publishStateChoices = $publishStateChoices;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->publishStateChoices
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'publish_state';
    }
}

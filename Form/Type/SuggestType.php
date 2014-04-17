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
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manhattan\Bundle\ConsoleBundle\Form\DataTransformer\ObjectToIdTransformer;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class SuggestType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;
    private $guesser;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, DoctrineOrmTypeGuesser $guesser)
    {
        $this->om = $om;
        $this->guesser = $guesser;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ObjectToIdTransformer($this->om);
        $builder->addModelTransformer($transformer);

        if (isset($options['class']) && (null === $options['class'])) {

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($transformer, $builder) {

                /* @var $form \Symfony\Component\Form\Form */
                $form = $event->getForm();
                $class = $form->getParent()->getConfig()->getDataClass();
                $property = $form->getName();
                $guessedType = $this->guesser->guessType($class, $property);
                $options = $guessedType->getOptions();

                $transformer->setObjectClass($options['class']);

            });

        } else {
            $transformer->setObjectClass($options['class']);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'class'
        ));
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'suggest_type';
    }

}

<?php
/*
 * This is the field display type that shows the associated
 * Asset image with a preview.
 */

namespace Manhattan\Bundle\ConsoleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class TelephoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'telephone';
    }
}

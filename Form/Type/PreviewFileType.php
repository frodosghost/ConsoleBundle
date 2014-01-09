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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Manhattan\Bundle\ConsoleBundle\Form\DataTransformer\FileTransformer;

/*
 * This is the field display type that shows the associated
 * Asset image with a preview.
 */
class PreviewFileType extends AbstractType
{
	/**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $preview = null;

        // Check if we have the Path available for the preview image
        if (is_object($view->parent->vars['value'])) {
            if (method_exists($view->parent->vars['value'], 'getWebPath')) {
                $preview = $view->parent->vars['value']->getWebPath();
            }
        }

        $view->vars = array_replace($view->vars, array(
            'type'  => 'file',
            'value' => '',
            'preview' => $preview
        ));
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'preview_file';
    }
}

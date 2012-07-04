<?php
/*
 * This is the field display type that shows the associated
 * Asset image with a preview.
 */

namespace Manhattan\Bundle\ConsoleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ObjectManager;

class PreviewFileType extends AbstractType
{
	/**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $preview = null;

        // Check if we have the Path available for the preview image
        if (is_object($view->getParent()->getVar('value'))) {
            if (method_exists($view->getParent()->getVar('value'), 'getWebPath')) {
                $preview = $view->getParent()->getVar('value')->getWebPath();
            }
        }
        
        $view->addVars(array(
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

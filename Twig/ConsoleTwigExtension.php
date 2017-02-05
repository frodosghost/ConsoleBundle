<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Twig Extension for displaying console partials
 *
 * @author James Rickard <james@frodosghost.com>
 */
class ConsoleTwigExtension extends \Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    private $environment;

    /**
     * @var Twig_Template
     */
    private $twigTemplate;

    /**
     * @var array
     */
    private $navigation;

    /**
     * @var string
     */
    private $template;

    /**
     * @param \Twig_Environment $environment
     * @param RegistryInterface $doctrine
     * @param string            $template
     */
    public function __construct(\Twig_Environment $environment, array $navigation, $template)
    {
        $this->environment = $environment;
        $this->navigation = $navigation;
        $this->template = $template;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('topHeader', array($this, 'topHeader'), array('is_safe' => array('html')))
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('slugify', array($this, 'slugify')),
        );
    }

    /**
     * Builds and returns Twig Template
     */
    public function getTemplate()
    {
        if (!$this->twigTemplate instanceof \Twig_Template) {
            $this->twigTemplate = $this->environment->loadTemplate($this->template);
        }

        return $this->twigTemplate;
    }

    /**
     * Renders analytics javascript
     *
     * @param  array $options
     * @return string
     */
    public function topHeader(array $options = array())
    {
        $html = $this->getTemplate()->renderBlock('topHeader', array(
            'navigation' => $this->navigation,
            'options'   => $options
        ));

        return $html;
    }

    /**
     * Slugifies string passed
     *
     * @param  string $string    String to make into a slug
     * @param  string $separator Separator replace spaces
     * @return string
     */
    public function slugify($string, $separator = '-')
    {
        $string = strtolower($string);
        $string = preg_replace('/([^a-z0-9]|-)+/', $separator, $string);
        $string = strtolower($string);

        return trim($string, $separator);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manhattan_console_twig';
    }
}

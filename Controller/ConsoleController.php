<?php

namespace Manhattan\Bundle\ConsoleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Manhattan\Bundle\ConsoleBundle\Form\UserType;


class ConsoleController extends Controller
{

    /**
     * Display index
     */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        return $this->render('ManhattanConsoleBundle:Console:index.html.twig');
    }

}

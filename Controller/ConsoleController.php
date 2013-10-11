<?php

namespace Manhattan\Bundle\ConsoleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Manhattan\Bundle\ConsoleBundle\Form\UserType;
/**
 * @Route("/console")
 */
class ConsoleController extends Controller
{

    /**
     * @Route("", name="console_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        return $this->render('ManhattanConsoleBundle:Console:index.html.twig');
    }

}

<?php

namespace Manhattan\Bundle\ConsoleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;


use Manhattan\Bundle\ConsoleBundle\Form\UserType;
/**
 * @Route("/console")
 */
class ConsoleController extends Controller
{

    /**
     * @Route("/", name="console_index")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        return $this->render('ManhattanConsoleBundle:Console:index.html.twig');
    }

    /**
     * @Route("/users", name="console_users")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @Template()
     */
    public function userslistAction()
    {
        $user_manager = $this->get('fos_user.user_manager');

        return array(
            'entities' => $user_manager->findUsers()
        );
    }

    /**
     * @Route("/users/{id}/edit", name="console_users_edit")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @Template()
     */
    public function userseditAction($id)
    {
        $user_manager = $this->get('fos_user.user_manager');

        $user = $user_manager->findUserBy(array('id' => $id));

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $user);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $user,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("/users/{id}/edit", name="console_users_update")
     * @Method({"POST"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @Template("ManhattanConsoleBundle:Console:edit.html.twig")
     */
    public function usersupdateAction(Request $request, $id)
    {
        $user_manager = $this->get('fos_user.user_manager');

        $user = $user_manager->findUserBy(array('id' => $id));

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $user);
        $deleteForm = $this->createDeleteForm($id);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $user_manager->updateUser($user);

            return $this->redirect($this->generateUrl('console_users_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

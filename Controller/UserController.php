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

use Manhattan\Bundle\ConsoleBundle\Entity\User;
use Manhattan\Bundle\ConsoleBundle\Form\UserType;

/**
 * @Route("/console/users")
 */
class UserController extends Controller
{

    /**
     * @Route("/", name="console_users")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @Template()
     */
    public function listAction()
    {
        $user_manager = $this->get('fos_user.user_manager');

        return array(
            'entities' => $user_manager->findUsers()
        );
    }

    /**
     * @Route("/new", name="console_users_new")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @Template()
     */
    public function newAction()
    {
        $user = new User();

        $createForm = $this->createForm(new UserType(), $user);

        return array(
            'user' => $user,
            'form' => $createForm->createView()
        );
    }

    /**
     * @Route("/create", name="console_users_create")
     * @Method({"POST"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @Template("ManhattanConsoleBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $user_manager = $this->get('fos_user.user_manager');

        $createForm = $this->createForm(new UserType(), $user);
        $createForm->bind($request);

        if ($createForm->isValid()) {
            /** Complete the Membership */
            $temp_password = substr(sha1(md5(uniqid(mt_rand(), true))), 0, 8);

            $user->setPlainPassword($temp_password);
            $user_manager->createUser($user);
            $user_manager->updateUser($user);

            return $this->redirect($this->generateUrl('console_users'));
        }

        return array(
            'user' => $user,
            'form' => $createForm->createView()
        );
    }


    /**
     * @Route("/{id}/edit", name="console_users_edit")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @Template()
     */
    public function editAction($id)
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
     * @Route("/{id}/update", name="console_users_update")
     * @Method({"POST"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     * @Template("ManhattanConsoleBundle:Console:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
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

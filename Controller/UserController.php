<?php

namespace Manhattan\Bundle\ConsoleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

use Manhattan\Bundle\ConsoleBundle\Entity\User;
use Manhattan\Bundle\ConsoleBundle\Form\UserType;

/**
 * @Route("/console")
 */
class UserController extends Controller
{

    /**
     * @Route("/users", name="console_users")
     * @Method({"GET"})
     * @Template()
     */
    public function listAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }

        $user_manager = $this->get('fos_user.user_manager');

        return array(
            'entities' => $user_manager->findUsers()
        );
    }

    /**
     * @Route("/users/new", name="console_users_new")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }

        $user = new User();

        $createForm = $this->createForm(new UserType(), $user);

        return array(
            'user' => $user,
            'form' => $createForm->createView()
        );
    }

    /**
     * @Route("/users/create", name="console_users_create")
     * @Method({"POST"})
     * @Template("ManhattanConsoleBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }

        $user = new User();
        $user_manager = $this->get('fos_user.user_manager');

        $createForm = $this->createForm(new UserType(), $user);
        $createForm->bind($request);

        if ($createForm->isValid()) {
            /** Complete the Membership */
            $temp_password = substr(sha1(md5(uniqid(mt_rand(), true))), 0, 8);

            $user->setPlainPassword($temp_password);
            $user_manager->createUser($user);

            $this->sendinitialemailAction($user);
            $user_manager->updateUser($user);

            return $this->redirect($this->generateUrl('console_users'));
        }

        return array(
            'user' => $user,
            'form' => $createForm->createView()
        );
    }


    /**
     * @Route("/users/{id}/edit", name="console_users_edit")
     * @Method({"GET"})
     * @Template()
     */
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }

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
     * @Route("/users/{id}/update", name="console_users_update")
     * @Method({"POST"})
     * @Template("ManhattanConsoleBundle:Console:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }

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

    /**
     * New Users can create their passwords
     *
     * @Route("/users/welcome/{token}", name="console_users_password_set")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function passwordsetAction(Request $request, $token)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $this->get('session')->setFlash('fos_user_success', 'You password has been set. Welcome to The Console.');

                    $url = $this->container->get('router')->generate('fos_user_profile_show');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return array(
            'token' => $token,
            'form'  => $form->createView(),
        );
    }

    /**
     * Deletes a User.
     *
     * @Route("/users/{id}", name="console_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }

        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ManhattanConsoleBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('console_users'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Configures User and inital email to send when User added
     *
     * @param  User $user
     */
    private function sendinitialemailAction($user)
    {
        $emails = $this->container->getParameter('console.users');

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $url = $this->get('router')->generate('console_users_password_set', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'url'  => $url,
            'console_name' => $emails['console_name']
        );

        $message = \Swift_Message::newInstance()
            ->setSubject($emails['subject'])
            ->setFrom($emails['from'])
            ->setTo(array($user->getEmail() => $user->getUsername()))
            ->setBody($this->renderView($emails['templates']['setpassword_html'], $context), 'text/html')
        ;
        $message->addPart($this->renderView($emails['templates']['setpassword_txt'], $context), 'text/plain');
        $message->getHeaders()->addTextHeader('X-SMTPAPI', '{"to": ["Testing <email+console@antelopestudios.com.au>", "'. $user->getUsername() .' <'. $user->getEmail() .'>"], "category": "'. $emails['subject'] .'"}');

        $this->get('mailer')->send($message);

        $user->setPasswordRequestedAt(new \DateTime());

    }
}

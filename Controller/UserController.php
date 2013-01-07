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

    /**
     * New Users can create their passwords
     * 
     * @Route("/welcome/{token}", name="console_users_password_set")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function passwordsetAction($token)
    {
        $user = $this->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw $this->createNotFoundException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $form = $this->get('fos_user.resetting.form');
        $formHandler = $this->get('fos_user.resetting.form.handler');
        $process = $formHandler->process($user);

        if ($process) {
            $this->get('session')->setFlash('fos_user_success', 'You password has been set. Welcome to The Console.');
            $response = new RedirectResponse($this->get('router')->generate('fos_user_profile_show'));

            try {
                $this->get('fos_user.security.login_manager')->loginUser(
                    $this->container->getParameter('fos_user.firewall_name'),
                    $user,
                    $response);
            } catch (AccountStatusException $ex) {
                // We simply do not authenticate users which do not pass the user
                // checker (not enabled, expired, etc.).
            }

            return $response;
        }

        return array(
            'token' => $token,
            'form'  => $form->createView(),
        );
    }

    /**
     * Deletes a User.
     *
     * @Route("/{id}", name="console_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
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

<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use DJBlaster\BlasterBundle\Entity\User;
use DJBlaster\BlasterBundle\Form\Type\UserType;
use DJBlaster\BlasterBundle\Utils\StringUtils;
use DJBlaster\BlasterBundle\Form\Type\ChangePasswordType;
use DJBlaster\BlasterBundle\Form\Model\ChangePassword;

class AdminUsersController extends Controller
{
    public function editUserAction(Request $request, $user_id)
    {
        $em = $this->get('doctrine')->getManager();

        if ($user_id == 0) {
            $user = new User();
        } else {

            $user = $em->getRepository('DJBlasterBundle:User')->find($user_id);
        }

        $action = $this->generateUrl('dj_blaster_admin_user_edit', array('user_id' => $user_id));
        $options = array('action' => $action);
        $form = $this->createForm(UserType::class, $user, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {

                $data = $form->getData();

                if ($user_id == 0) {
                    $stringUtil = new StringUtils();
                    //Generate a unique password
                    $password = $stringUtil->generateRandomString(10);

                    //Generate a unique salt
                    $salt = uniqid('', true);
                    $data->setSalt($salt);

                    $encoder = $this->container->get('security.password_encoder');

                    $encPass = $encoder->encodePassword($user, $password, $salt);

                    $data->setPassword($encPass);

                    $info = array(
                        'password' => $password,
                        'name' => $data->getName()
                    );
                    $this->_emailRegistration($data->getEmail(), $info);
                }
                $em->persist($data);
                $em->flush();

                return $this->redirect($this->generateUrl('dj_blaster_admin_user_list'));
            }
        }

        return $this->render('DJBlasterBundle:Admin/User:user_form.html.twig', array(
            'form' => $form->createView(),
            'user_id' => $user_id,
        ));
    }

    public function deleteUserAction(Request $request, SessionInterface $session, User $user)
    {

        if (!$user) {
            throw $this->createNotFoundException("No user found.");
        }

        $session->getFlashBag()->add('user-notices', 'Administrator ' . $user->getName() . ' was removed.');

        $em = $this->get('doctrine')->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('dj_blaster_admin_user_list'));
    }

    public function listUsersAction(Request $request, SessionInterface $session)
    {

        $users = $this->getDoctrine()->getRepository('DJBlasterBundle:User')->findAllOrderedByName();

        $notices = $session->getFlashBag()->get('user-notices', array());

        return $this->render('DJBlasterBundle:Admin/User:user_list.html.twig', array(
            'users' => $users,
            'notices' => $notices,
        ));
    }

    public function changePasswordAction(Request $request, SessionInterface $session)
    {
        $error = '';

        $changePasswordModel = new ChangePassword();
        $action = $this->generateUrl('dj_blaster_admin_login_reset');

        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);

        $user = $this->getUser();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                $em = $this->get('doctrine')->getManager();

                $encoder = $this->container->get('security.password_encoder');

                $encPass = $encoder->encodePassword($user, $data->getNewPassword(), $user->getSalt());

                $user->setPassword($encPass);

                $em->persist($user);
                $em->flush();

                $session->getFlashBag()->add('admin-notices', 'Your password has been changed.');
                return $this->redirect($this->generateUrl('dj_blaster_admin_home'));
            }
        }

        return $this->render('DJBlasterBundle:Admin/User:change_password_form.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    private function _emailRegistration($recipientEmailAddress, $info)
    {
        $mailer = $this->get('mailer');
        $from_address = $this->container->getParameter('mailer_from_address');
        $from_name = $this->container->getParameter('mailer_from_name');
        $message = $mailer->createMessage()
            ->setSubject("DJ Blaster Administrator Registration Information")
            ->setTo($recipientEmailAddress)
            ->setFrom(array($from_address => $from_name))
            ->setBody($this->renderView('DJBlasterBundle:Emails:registration.html.twig', array('info' => $info)), 'text/html');
        return $mailer->send($message);
    }
}
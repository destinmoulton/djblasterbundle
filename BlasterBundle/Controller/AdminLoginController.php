<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use DJBlaster\BlasterBundle\Entity\User;
use DJBlaster\BlasterBundle\Utils\StringUtils;

class AdminLoginController extends Controller {

    public function loginAction(Request $request) {
        $session = $this->getRequest()->getSession();
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastEmail = $authenticationUtils->getLastUsername();

        $notices = $session->getFlashBag()->get('login-notices', array());

        return $this->render('DJBlasterBundle:Admin/Login:login_form.html.twig', array(
            'last_email' => $lastEmail,
            'error' => $error,
            'notices' => $notices,
        ));
    }

    public function loginCheckAction() {
        /*
         This should remain empty.
         This is a stub function for the security authenticator route.
         */
    }

    public function resetAction(Request $request) {
        $error = '';

        $action = $this->generateUrl('dj_blaster_admin_login_reset');

        $form = $this->createFormBuilder()->setAction($action)->add('email', 'text')->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $data = $form->getData();

            //$email = $form->get('email')->getData();

            $user = $this->getDoctrine()->getRepository('DJBlasterBundle:User')->findOneByEmail($data['email']);

            if ($user) {
                $session = $this->getRequest()->getSession();
                $em = $this->getDoctrine()->getEntityManager();

                $encoder = $this->container->get('security.password_encoder');

                $stringUtil = new StringUtils();
                //Generate a unique password
                $password = $stringUtil->generateRandomString(10);

                $encPass = $encoder->encodePassword($user, $password, $user->getSalt());

                $user->setPassword($encPass);

                $em->persist($user);
                $em->flush();

                $info = array(
                    'name' => $user->getName(),
                    'new_password' => $password
                );
                $this->_emailPasswordReset($user->getEmail(), $info);
                $session->getFlashBag()->add('login-notices', 'Your password was reset. Check your email (' . $user->getEmail() . ').');
                return $this->redirect($this->generateUrl('dj_blaster_admin_login'));
            } else {
                $error = "Unable to find that email address.";

            }
        }

        return $this->render('DJBlasterBundle:Admin/Login:reset_password_form.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    private function _emailPasswordReset($recipientEmailAddress, $info) {
        $mailer = $this->get('mailer');

        $message = $mailer->createMessage()->setSubject("DJ Blaster Password Reset")->setTo($recipientEmailAddress)->setFrom('ai.destin@gmail.com')->setBody($this->renderView('DJBlasterBundle:Emails:password_reset.html.twig', array('info' => $info)), 'text/html');
        return $mailer->send($message);
    }

}

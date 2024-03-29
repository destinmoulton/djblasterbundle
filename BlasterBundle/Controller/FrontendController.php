<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Welp\MailchimpBundle\Event\SubscriberEvent;
use Welp\MailchimpBundle\Subscriber\Subscriber;

use DJBlaster\BlasterBundle\Entity\DJSignIn;
use DJBlaster\BlasterBundle\Entity\DJNotification;
use DJBlaster\BlasterBundle\Form\Type\DJSignInType;

class FrontendController extends Controller
{

    private function isShowStillGoing(SessionInterface $session)
    {
        if ($session->has('djsignin_information')) {
            $sess = $session->get('djsignin_information');

            $dt_now = new \DateTime();
            $dt_end_show = \DateTime::createFromFormat("H:i:s", $sess["show_end_time"]);
            $dt_start_show = \DateTime::createFromFormat("H:i:s", $sess["show_start_time"]);

            // Determine the sign of the time difference
            // 00:00 (12:00 am) will occur *before* the end time (so signs get swapped)
            $diff_start_end = $dt_start_show->diff($dt_end_show);
            $expected_sign = $diff_start_end->format("%R"); // Get '+' or '-'

            $dt_diff = $dt_now->diff($dt_end_show);
            if ($dt_diff->format("%R") === $expected_sign) {
                return true;
            }
        }
        return false;
    }

    public function homeAction(SessionInterface $session)
    {
        if (!$this->isShowStillGoing($session)) {
            return $this->redirect($this->generateUrl('dj_blaster_djsignin'));
        }

        $em = $this->get('doctrine')->getManager();

        // DJ Notification is id 1 for the header notification
        $header_notification = $em->getRepository('DJBlasterBundle:DJNotification')->find(1);
        // DJ Notification is id 2 for the popup notice
        $popup_notification = $em->getRepository('DJBlasterBundle:DJNotification')->find(2);

        $data = array(
            'header_notification'=>$header_notification,
            'popup_notification'=>$popup_notification,
            'djsignin_information' => $session->get('djsignin_information')
        );
        return $this->render('DJBlasterBundle::dj_main.html.twig', $data);
    }

    public function djsignoutAction(Request $request, SessionInterface $session)
    {
        $session->remove('djsignin_information');
        return $this->redirect($this->generateUrl('dj_blaster_djsignin'));
    }

    public function djsigninAction(Request $request, SessionInterface $session)
    {
        if ($this->isShowStillGoing($session)) {
            // DJ still signed in: Redirect to the standard home page
            return $this->redirect($this->generateUrl('dj_blaster_home'));
        }

        $em = $this->get('doctrine')->getManager();


        $action = $this->generateUrl('dj_blaster_djsignin');
        $options = array('action' => $action);
        $form = $this->createForm(DJSignInType::class, new DJSignIn, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $data = $form->getData();

                // Automatically add people to the MailChimp mailing list
                // The configuration options are set in parameters.yml and config.yml
                $mailchimp_list_id = $this->container->getParameter('mailchimp_list_id');
                $subscriber = new Subscriber($data->getDjEmail(), [
                    'FNAME' => $data->getDjFirstName(),
                    'LNAME' => $data->getDjLastName(),
                ], [
                    'language' => 'en'
                ]);

                $this->container->get('event_dispatcher')->dispatch(
                    SubscriberEvent::EVENT_SUBSCRIBE,
                    new SubscriberEvent($mailchimp_list_id, $subscriber)
                );

                $em->persist($data);
                $em->flush();

                $session_data = array(
                    'dj_first_name' => $data->getDjFirstName(),
                    'dj_last_name' => $data->getDjLastName(),
                    'dj_email' => $data->getDjEmail(),
                    'show_start_time' => $data->getShowStartTime()->format('H:i:s'),
                    'show_end_time' => $data->getShowEndTime()->format('H:i:s')
                );
                $session->set('djsignin_information', $session_data);

                return $this->redirect($this->generateUrl('dj_blaster_home'));
            }
        } else {
            // Clear the session information
            $session->remove('djsignin_information');
        }


        return $this->render('DJBlasterBundle::dj_signin.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function clearCacheAction()
    {
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));
        return $this->redirect($this->generateUrl('dj_blaster_home'));
    }
}
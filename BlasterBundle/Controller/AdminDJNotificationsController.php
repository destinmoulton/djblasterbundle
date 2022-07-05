<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use DJBlaster\BlasterBundle\Entity\DJNotification;
use DJBlaster\BlasterBundle\Form\Type\DJNotificationType;

class AdminDJNotificationsController extends Controller
{
    public function editDJNotificationAction(Request $request, SessionInterface $session, $notification_id)
    {
        $em = $this->get('doctrine')->getManager();

        $notification = $em->getRepository('DJBlasterBundle:DJNotification')->find($notification_id);

        $action = $this->generateUrl('dj_blaster_admin_djnotification_edit', array(
            'notification_id' => $notification_id,
        ));
        $options = array('action' => $action);
        $form = $this->createForm(DJNotificationType::class, $notification, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $data = $form->getData();

                $em->persist($data);
                $em->flush();

                return $this->redirect($this->generateUrl(
                    'dj_blaster_admin_djnotification_list',
                    array(
                    )
                ));
            }
        }

        return $this->render('DJBlasterBundle:Admin/DJNotifications:djnotifications_form.html.twig', array(
            'form' => $form->createView(),
            'notification' => $notification,
        ));
    }

    public function listDJNotificationsAction(SessionInterface $session)
    {
        $notifications = $this->getDoctrine()->getRepository('DJBlasterBundle:DJNotification')->findAll();

        $notices = $session->getFlashBag()->get('djnotification-notices', array());

        return $this->render('DJBlasterBundle:Admin/DJNotifications:djnotifications_list.html.twig', array(
            'notifications' => $notifications,
            'notices' => $notices,
        ));
    }

}
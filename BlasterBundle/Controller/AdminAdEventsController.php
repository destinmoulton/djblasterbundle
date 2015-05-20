<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;
use DJBlaster\BlasterBundle\Entity\AdEvent;
use DJBlaster\BlasterBundle\Form\Type\AdEventType;

class AdminAdEventsController extends Controller {
    public function editAdEventAction(Request $request, Customer $customer, CustomerCampaign $campaign, $event_id) {
        $em = $this->get('doctrine')->getManager();
    
        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }
        
        if (!$campaign) {
            throw $this->createNotFoundException("No campaign found.");
        }

        if ($event_id == 0) {
            $adEvent = new AdEvent();
        } else {

            $adEvent = $em->getRepository('DJBlasterBundle:AdEvent')->find($event_id);
        }

        $action = $this->generateUrl('dj_blaster_admin_ad_event_edit', array(
            'customer' => $customer->getId(),
            'campaign' => $campaign->getCampaignId(),
            'event_id' => $event_id
        ));
        $options = array('action' => $action);
        $form = $this->createForm(new AdEventType(), $adEvent, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $session = $this->getRequest()->getSession();
                $data = $form->getData();

                $data->setCustomer($customer);
                $data->setCampaign($campaign);
                
                //Calculate the number of days between the start and end of the campaign
                $startDate = $campaign->getStartDate();
                $endDate = $campaign->getEndDate();
                
                $daysDiff = $endDate->diff($startDate)->format("%a");
                
                //Calculate and store the number of reads per day
                $no_reads_per_day = ceil($data->getNoReads()/$daysDiff);
                $data->setNoReadsPerDay($no_reads_per_day);
                

                $em->persist($data);
                $em->flush();
                if ($event_id == 0) {
                    $session->getFlashBag()->add('ad-notices', $data->getAdName() . ' event added.');
                } else {
                    $session->getFlashBag()->add('ad-notices', $data->getAdName() . ' event saved.');
                }
                
                return $this->redirect($this->generateUrl('dj_blaster_admin_ad_list', array(
                    'customer' => $customer->getId(),
                    'campaign' => $campaign->getCampaignId(),
                )));

            }
        }

        return $this->render('DJBlasterBundle:Admin/Campaign:ad_event_form.html.twig', array(
            'form' => $form->createView(),
            'campaign' => $campaign,
            'customer'=>$customer,
            'event' => $adEvent,
        ));
    }

    public function deleteAdEventAction(Request $request, Customer $customer, CustomerCampaign $campaign, AdEvent $event) {
        $session = $this->getRequest()->getSession();
        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }
        
        if (!$campaign) {
            throw $this->createNotFoundException("No campaign found.");
        }
        
        if (!$event) {
            throw $this->createNotFoundException("No event found.");
        }

        $session->getFlashBag()->add('ad-notices', $event->getAdName() . ' was removed.');

        $em = $this->get('doctrine')->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirect($this->generateUrl('dj_blaster_admin_ad_list', array(
            'customer' => $customer->getId(),
            'campaign' => $campaign->getCampaignId(),
        )));

    }

}

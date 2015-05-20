<?php

namespace DJBlaster\BlasterBundle\Controller;

use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;
use DJBlaster\BlasterBundle\Entity\AdShowSponsorship;
use DJBlaster\BlasterBundle\Form\Type\AdShowSponsorshipType;

class AdminAdShowSponsorshipsController extends Controller {
    public function editAdShowSponsorshipAction(Request $request, Customer $customer, CustomerCampaign $campaign, $sponsorship_id) {
        $em = $this->get('doctrine')->getManager();

        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }
        
        if (!$campaign) {
            throw $this->createNotFoundException("No campaign found.");
        }
        
        if ($sponsorship_id == 0) {
            $adShowSponsorship = new AdShowSponsorship();
        } else {

            $adShowSponsorship = $em->getRepository('DJBlasterBundle:AdShowSponsorship')->find($sponsorship_id);
        }

        $action = $this->generateUrl('dj_blaster_admin_ad_show_sponsorship_edit', array(
            'customer' => $customer->getId(),
            'campaign' => $campaign->getCampaignId(),
            'sponsorship_id' => $sponsorship_id
        ));
        $options = array('action' => $action);
        $form = $this->createForm(new AdShowSponsorshipType(), $adShowSponsorship, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $session = $this->getRequest()->getSession();
                $data = $form->getData();

                $data->setCustomer($customer);
                $data->setCampaign($campaign);
                
                
                $em->persist($data);
                $em->flush();
                if ($sponsorship_id == 0) {
                    $session->getFlashBag()->add('ad-notices', $data->getAdName() . ' show sponsorship added.');
                } else {
                    $session->getFlashBag()->add('ad-notices', $data->getAdName() . ' show sponsorship saved.');
                }
                
                return $this->redirect($this->generateUrl('dj_blaster_admin_ad_list', array(
                    'customer' => $customer->getId(),
                    'campaign' => $campaign->getCampaignId(),
                )));

            }
        }

        return $this->render('DJBlasterBundle:Admin/Campaign:ad_show_sponsorship_form.html.twig', array(
            'form' => $form->createView(),
            'campaign' => $campaign,
            'customer' => $customer,
            'sponsorship' => $adShowSponsorship,
        ));
    }

    public function deleteAdShowSponsorshipAction(Request $request, Customer $customer, CustomerCampaign $campaign, AdShowSponsorship $sponsorship) {
        $session = $this->getRequest()->getSession();
        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }
        
        if (!$campaign) {
            throw $this->createNotFoundException("No campaign found.");
        }
        
        if (!$sponsorship) {
            throw $this->createNotFoundException("No show sponsorship found.");
        }
        
        $session->getFlashBag()->add('ad-notices', $sponsorship->getAdName() . ' was removed.');

        $em = $this->get('doctrine')->getManager();
        $em->remove($sponsorship);
        $em->flush();

        return $this->redirect($this->generateUrl('dj_blaster_admin_ad_list', array(
            'customer' => $customer->getId(),
            'campaign' => $campaign->getCampaignId(),
        )));

    }

}

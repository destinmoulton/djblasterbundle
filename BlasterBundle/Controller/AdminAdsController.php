<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;
use DJBlaster\BlasterBundle\Entity\AdShowSponsorship;

class AdminAdsController extends Controller {
    
    public function listAdsAction(Customer $customer, CustomerCampaign $campaign) {
        $session = $this->getRequest()->getSession();

        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }
        
        if (!$campaign) {
            throw $this->createNotFoundException("No campaign found.");
        }

        $show_sponsorships = $this->getDoctrine()
                                  ->getRepository('DJBlasterBundle:AdShowSponsorship')
                                  ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);

        $events = $this->getDoctrine()
                       ->getRepository('DJBlasterBundle:AdEvent')
                       ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);
                       
         $psas = $this->getDoctrine()
                       ->getRepository('DJBlasterBundle:AdPSA')
                       ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);

        $notices = $session->getFlashBag()->get('ad-notices', array());

        return $this->render('DJBlasterBundle:Admin/Campaign:ad_list.html.twig', array(
            'show_sponsorships'=>$show_sponsorships,
            'events'=>$events,
            'psas'=>$psas,
            'customer' => $customer,
            'campaign' => $campaign,
            'notices' => $notices,
        ));
    }
}
<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;
use DJBlaster\BlasterBundle\Form\Type\CustomerCampaignType;

class AdminCustomerCampaignsController extends Controller {
    public function editCustomerCampaignAction(Request $request, Customer $customer, $campaign_id) {
        $em = $this->get('doctrine')->getManager();

        if ($campaign_id == 0) {
            $customerCampaign = new CustomerCampaign();
        } else {

            $customerCampaign = $em->getRepository('DJBlasterBundle:CustomerCampaign')->find($campaign_id);
        }

        $action = $this->generateUrl('dj_blaster_admin_campaign_edit', array(
            'customer' => $customer->getId(),
            'campaign_id' => $campaign_id
        ));
        $options = array('action' => $action);
        $form = $this->createForm(new CustomerCampaignType(), $customerCampaign, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $session = $this->getRequest()->getSession();
                $data = $form->getData();

                $data->setCustomer($customer);

                $em->persist($data);
                $em->flush();
                
                return $this->redirect($this->generateUrl('dj_blaster_admin_ad_list', 
                                                            array('customer' => $customer->getId(),
                                                                  'campaign' => $data->getCampaignId())));

            }
        }

        return $this->render('DJBlasterBundle:Admin/Campaign:campaign_form.html.twig', array(
            'form' => $form->createView(),
            'customer'=>$customer,
            'campaign' => $customerCampaign,
        ));
    }

    public function listCustomerCampaignsAction(Customer $customer) {
        $session = $this->getRequest()->getSession();

        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }

        $campaigns = $this->getDoctrine()->getRepository('DJBlasterBundle:CustomerCampaign')->findAllOrderedByNameForCustomer($customer);

        $notices = $session->getFlashBag()->get('campaign-notices', array());

        
        return $this->render('DJBlasterBundle:Admin/Campaign:campaign_list.html.twig', array(
            'campaigns' => $campaigns,
            'customer' => $customer,
            'notices' => $notices,
        ));
    }

    public function deleteCustomerCampaignAction(Request $request, Customer $customer, CustomerCampaign $campaign) {
        $session = $this->getRequest()->getSession();
        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }
        
        if (!$campaign) {
            throw $this->createNotFoundException("No campaign found.");
        }

        $session->getFlashBag()->add('campaign-notices', $campaign->getCampaignName() . ' was removed.');

        $em = $this->get('doctrine')->getManager();
        $em->remove($campaign);
        $em->flush();

        return $this->redirect($this->generateUrl('dj_blaster_admin_campaign_list', array('customer'=>$customer->getId())));
    }

}

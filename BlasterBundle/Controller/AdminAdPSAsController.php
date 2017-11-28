<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;
use DJBlaster\BlasterBundle\Entity\AdPSA;
use DJBlaster\BlasterBundle\Form\Type\AdPSAType;

class AdminAdPSAsController extends Controller {
    public function editAdPSAAction(Request $request, SessionInterface $session, Customer $customer, CustomerCampaign $campaign, $psa_id) {
        $em = $this->get('doctrine')->getManager();
    
        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }
        
        if (!$campaign) {
            throw $this->createNotFoundException("No campaign found.");
        }

        if ($psa_id == 0) {
            $adPSA = new AdPSA();
        } else {

            $adPSA = $em->getRepository('DJBlasterBundle:AdPSA')->find($psa_id);
        }

        $action = $this->generateUrl('dj_blaster_admin_ad_psa_edit', array(
            'customer' => $customer->getId(),
            'campaign' => $campaign->getCampaignId(),
            'psa_id' => $psa_id
        ));
        $options = array('action' => $action);
        $form = $this->createForm(AdPSAType::class, $adPSA, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $data = $form->getData();

                $data->setCustomer($customer);
                $data->setCampaign($campaign);
                

                $em->persist($data);
                $em->flush();
                if ($psa_id == 0) {
                    $session->getFlashBag()->add('ad-notices', $data->getAdName() . ' PSA added.');
                } else {
                    $session->getFlashBag()->add('ad-notices', $data->getAdName() . ' PSA saved.');
                }
                
                return $this->redirect($this->generateUrl('dj_blaster_admin_ad_list', array(
                    'customer' => $customer->getId(),
                    'campaign' => $campaign->getCampaignId(),
                )));

            }
        }

        return $this->render('DJBlasterBundle:Admin/Campaign:ad_psa_form.html.twig', array(
            'form' => $form->createView(),
            'campaign' => $campaign,
            'customer'=>$customer,
            'psa' => $adPSA,
        ));
    }

    public function deleteAdPSAAction(Request $request, SessionInterface $session, Customer $customer, CustomerCampaign $campaign, AdPSA $psa) {

        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }
        
        if (!$campaign) {
            throw $this->createNotFoundException("No campaign found.");
        }
        
        if (!$psa) {
            throw $this->createNotFoundException("No psa found.");
        }

        $session->getFlashBag()->add('ad-notices', $psa->getAdName() . ' PSA was removed.');

        $em = $this->get('doctrine')->getManager();
        $em->remove($psa);
        $em->flush();

        return $this->redirect($this->generateUrl('dj_blaster_admin_ad_list', array(
            'customer' => $customer->getId(),
            'campaign' => $campaign->getCampaignId(),
        )));

    }

}

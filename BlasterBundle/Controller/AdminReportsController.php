<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;
use DJBlaster\BlasterBundle\Entity\AdEvent;
use DJBlaster\BlasterBundle\Entity\AdPSA;
use DJBlaster\BlasterBundle\Entity\AdShowSponsorship;
use DJBlaster\BlasterBundle\Entity\DJReadEvent;
use DJBlaster\BlasterBundle\Entity\DJReadPSA;
use DJBlaster\BlasterBundle\Entity\DJReadShowSponsorship;

class AdminReportsController extends Controller {
    public function reportGeneratorAction(Request $request, $customer_id, $campaign_id, $action){
        $em = $this->getDoctrine()->getEntityManager();
        $customers_select = $this->getDoctrine()
                                 ->getRepository('DJBlasterBundle:Customer')
                                 ->findAllOrderedByName();
                                 
        $campaigns_list = array();
        $shows_list = array();
        $events_list = array();
        $psas_list = array();
        $dj_show_reads = array();
        $dj_event_reads = array();
        $dj_psa_reads = array();
        $campaigns_select = array();
        $campaigns = false;
        $customer = false;
        if($customer_id > 0){
            $customer = $em->getRepository('DJBlasterBundle:Customer')
                           ->find($customer_id);
            $campaigns_select = $this->getDoctrine()
                                     ->getRepository('DJBlasterBundle:CustomerCampaign')
                                     ->findAllOrderedByNameForCustomer($customer);
            
        }
        
        if($action == 'generate' || $action == 'pdf'){
            if($campaign_id==0){
                foreach($campaigns_select as $campaign){
                    $campaigns_list[$campaign->getCampaignId()] = $campaign;
                    
                    $currShows = $this->getDoctrine()
                                       ->getRepository('DJBlasterBundle:AdShowSponsorship')
                                       ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);
                    $shows_list[$campaign->getCampaignId()] = $currShows;
                    
                    $currEvents = $this->getDoctrine()
                                       ->getRepository('DJBlasterBundle:AdEvent')
                                       ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);
                    $events_list[$campaign->getCampaignId()] = $currEvents;
                    
                    $currPSAs = $this->getDoctrine()
                                     ->getRepository('DJBlasterBundle:AdPSA')
                                     ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);
                    $psas_list[$campaign->getCampaignId()] = $currPSAs;
                }
            } else {
                $campaign = $em->getRepository('DJBlasterBundle:CustomerCampaign')
                               ->find($campaign_id);
                $campaigns_list[$campaign_id] = $campaign;
                
                $currShows = $this->getDoctrine()
                                       ->getRepository('DJBlasterBundle:AdShowSponsorship')
                                       ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);
                $shows_list[$campaign->getCampaignId()] = $currShows;
                
                $currEvents = $this->getDoctrine()
                                   ->getRepository('DJBlasterBundle:AdEvent')
                                   ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);
                $events_list[$campaign_id] = $currEvents;
                
                $currPSAs = $this->getDoctrine()
                                 ->getRepository('DJBlasterBundle:AdPSA')
                                 ->findAllOrderedByNameForCustomerAndCampaign($customer, $campaign);
                $psas_list[$campaign->getCampaignId()] = $currPSAs;
            }
            
            foreach($campaigns_list as $camp_id => $campaign){
                
                foreach($shows_list[$camp_id] as $show){
                    $dj_show_reads[$show->getSponsorshipId()] = $this->getDoctrine()
                                                                     ->getRepository('DJBlasterBundle:DJReadShowSponsorship')
                                                                     ->findAllForShow($show);
                } 
                
                foreach($events_list[$camp_id] as $event){
                    $dj_event_reads[$event->getEventId()] = $this->getDoctrine()
                                                                 ->getRepository('DJBlasterBundle:DJReadEvent')
                                                                 ->findAllForEvent($event);
                } 
                
                foreach($psas_list[$camp_id] as $psa){
                    $dj_psa_reads[$psa->getPsaId()] = $this->getDoctrine()
                                                                 ->getRepository('DJBlasterBundle:DJReadPSA')
                                                                 ->findAllForPSA($psa);
                } 
            }
            
            
        }
        
        $options = array(
            'action' => $action,
            'customer_id' => $customer_id,
            'campaign_id' => $campaign_id,
            'customers_select' => $customers_select,
            'campaigns_select' => $campaigns_select,
            'customer' => $customer,
            'campaigns_list'=>$campaigns_list,
            'shows_list'=>$shows_list,
            'events_list'=>$events_list,
            'psas_list'=>$psas_list,
            'dj_show_reads'=>$dj_show_reads,
            'dj_event_reads'=>$dj_event_reads,
            'dj_psa_reads'=>$dj_psa_reads
        );
        
        if($action=='pdf'){
            // Disable the symfony profile so it doesn't interfere with report generation
            if ($this->container->has('profiler'))
            {
                $this->container->get('profiler')->disable();
            }
            $html = $this->renderView('DJBlasterBundle:Admin/Report:pdf_generator.html.twig', $options);
            $title = $customer->getName()."_".date('d-m-Y_H_i_s').".pdf";
            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="'.$title.'"'
                )
            );
        } else {
            return $this->render('DJBlasterBundle:Admin/Report:report_selector.html.twig', $options);
        }
        
    }
} 
<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use DJBlaster\BlasterBundle\Entity\DJReadPSA;

class AdminController extends Controller {
    public function indexAction() {
        $active_campaigns = $this->getDoctrine()
            ->getRepository('DJBlasterBundle:CustomerCampaign')
            ->getActiveCampaigns();

        $active_campaign_ads = $this->_collateCampaignAds($active_campaigns);

        $sponsorship_reads = $this->getDoctrine()
            ->getRepository('DJBlasterBundle:DJReadShowSponsorship')
            ->getRecentReads(40);

        $event_reads = $this->getDoctrine()
            ->getRepository('DJBlasterBundle:DJReadEvent')
            ->getRecentReads(40);

        $psa_reads = $this->getDoctrine()
            ->getRepository('DJBlasterBundle:DJReadPSA')
            ->getRecentReads(40);

        return $this->render('DJBlasterBundle:Admin:admin_index.html.twig', array(
            'active_campaigns'=>$active_campaigns,
            'active_campaign_ads'=>$active_campaign_ads,
            'sponsorship_reads'=>$sponsorship_reads,
            'event_reads'=>$event_reads,
            'psa_reads'=>$psa_reads
        ));
    }

    // Get all the Show Sponsorships, Events/Promotions, and PSAs for a campaign
    private function _collateCampaignAds($campaigns){
        $collate = array();
        foreach($campaigns as $camp){
            $campaignObj = $this->getDoctrine()
                            ->getRepository('DJBlasterBundle:CustomerCampaign')
                            ->find($camp['campaign_id']);
            //echo($camp['campaign_id']);die();
            $collate[$camp['campaign_id']] = array();
            $collate[$camp['campaign_id']]['sponsorships'] = $this->getDoctrine()
                ->getRepository('DJBlasterBundle:AdShowSponsorship')
                ->findAllOrderedByNameForCampaignId($campaignObj);

            $collate[$camp['campaign_id']]['events'] = $this->getDoctrine()
                ->getRepository('DJBlasterBundle:AdEvent')
                ->findAllOrderedByNameForCampaignId($campaignObj);
            
            $collate[$camp['campaign_id']]['psas'] = $this->getDoctrine()
                ->getRepository('DJBlasterBundle:AdPSA')
                ->findAllOrderedByNameForCampaignId($campaignObj);

        }

        return $collate;
    }
}

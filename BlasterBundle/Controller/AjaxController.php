<?php

namespace DJBlaster\BlasterBundle\Controller;

use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

use DJBlaster\BlasterBundle\Entity\CustomerCampaign;
use DJBlaster\BlasterBundle\Entity\AdShowSponsorship;
use DJBlaster\BlasterBundle\Entity\AdPSA;
use DJBlaster\BlasterBundle\Entity\AdEvent;
use DJBlaster\BlasterBundle\Entity\DJReadShowSponsorship;
use DJBlaster\BlasterBundle\Entity\DJReadPSA;
use DJBlaster\BlasterBundle\Entity\DJReadEvent;

class AjaxController extends Controller
{
    public function getShowSponsorshipsAction(Request $request)
    {
        var_dump(date("g:i"));
        die();
        $params = json_decode($request->getContent());

        $sponsorships = $this->getDoctrine()->getRepository('DJBlasterBundle:AdShowSponsorship')
            ->findAllForHour($params->current_hour, $params->current_time);

        $serializedEntity = $this->container->get('jms_serializer')->serialize($sponsorships, 'json');
        return new JsonResponse($serializedEntity);
    }

    public function getPSAsAction(Request $request)
    {

        $post = json_decode($request->getContent());

        $psas = $this->getDoctrine()->getRepository('DJBlasterBundle:AdPSA')
            ->findOneLastRead($post->current_time);

        $serializedEntity = $this->container->get('jms_serializer')->serialize($psas, 'json');
        return new JsonResponse($serializedEntity);
    }

    public function getEventsAction(Request $request)
    {

        $post = json_decode($request->getContent());

        // Try to get an event that has not been read today
        $event = $this->getDoctrine()->getRepository('DJBlasterBundle:AdEvent')
            ->findUnreadToday($post->current_time);

        if (!$event) {
            // All events have been read at least once today
            // try to get an event that should be read next 
            $event = $this->getDoctrine()->getRepository('DJBlasterBundle:AdEvent')
                ->findNextToRead($post->current_time, $post->current_hour);
        }

        $serializedEntity = $this->container->get('jms_serializer')->serialize($event, 'json');
        return new JsonResponse($serializedEntity);
    }

    public function eventReadItAction(Request $request, AdEvent $event)
    {
        if (!$event) {
            return new JsonResponse(array('status' => 'error', 'message' => 'Unable to find that event.'));
        }

        $post = json_decode($request->getContent());

        if (!$post->dj_initials || !$post->current_time || !$post->current_hour) {
            return new JsonResponse(array('status' => 'error', 'message' => 'DJ Initials or Current Time not set.'));
        }

        $em = $this->get('doctrine')->getManager();

        $djRead = new DJReadEvent();

        $djRead->setDjInitials($post->dj_initials);
        $djRead->setEvent($event);
        $em->persist($djRead);
        $em->flush();

        $lastReadDateTime = $event->getLastReadDate();
        $lastReadDate = $lastReadDateTime->format('Y-m-d');

        $currentDateTime = new DateTime();
        $currentDateTime->setTimestamp($post->current_time);
        $currentDate = $currentDateTime->format('Y-m-d');

        if ($currentDate != $lastReadDate) {
            $campaign = $event->getCampaign();

            // First read of the day, 
            // so calculate the number of reads to perform
            // and subtract one (because of this read) 
            $campaignEndDate = $campaign->getEndDate();

            // Create a new DateTime object (so time isn't a factor)
            $freshCurrentDateTime = new DateTime($currentDate);

            // Get the difference
            $daysUntilCampaignEnd = $campaignEndDate->diff($freshCurrentDateTime)->format("%a");

            //Include today
            $daysUntilCampaignEnd = $daysUntilCampaignEnd + 1;

            // How many reads are left?
            $no_reads_performed = $event->getNoReadsPerformed();
            $reads_remaining = $event->getNoReads() - $no_reads_performed;

            // Calculate and store the number of reads left today
            $no_reads_per_day = ceil($reads_remaining / $daysUntilCampaignEnd);

            $no_reads_remaining_today = $no_reads_per_day - 1;

            $event->setNoReadsRemainingToday($no_reads_remaining_today);
        } else {
            // Subtract from the number to read today
            $no_reads_remaining_today = $event->getNoReadsRemainingToday();
            $no_reads_remaining_today = $no_reads_remaining_today - 1;
            $event->setNoReadsRemainingToday($no_reads_remaining_today);
        }

        if ($no_reads_remaining_today > 0) {
            // Setup the next time to read this event ad today
            $hour_diff = (int)$post->settings->adEvent->end_hour - (int)$post->current_hour;

            // Calculate the gap before the event should be read next
            $hour_gap = floor($hour_diff / ($no_reads_remaining_today + 1));

            $next_read_hour = $hour_gap + (int)$post->current_hour;
            $nextReadDateTime = new DateTime();
            $nextReadDateTime->setTime($next_read_hour, 0, 0);

            $event->setNextReadTime($nextReadDateTime);
        } else {
            // For query insurance, lets set the next read time
            // for an event that shouldn't be read 
            // outside of the query bounds.
            $nextReadDateTime = new DateTime();
            $nextReadDateTime->setTime((int)$post->settings->adEvent->end_hour + 1, 0, 0);

            $event->setNextReadTime($nextReadDateTime);
        }

        // Increment the total number of reads performed
        $no_reads_performed = $event->getNoReadsPerformed();
        $event->setNoReadsPerformed($no_reads_performed + 1);


        $event->setLastRead($currentDateTime);
        $event->setLastReadDate($currentDateTime);
        $em->persist($event);
        $em->flush();
        return new JsonResponse(array('status' => 'success'));
    }

    public function showSponsorshipReadItAction(Request $request, AdShowSponsorship $sponsorship)
    {

        if (!$sponsorship) {
            return new JsonResponse(array('status' => 'error', 'message' => 'Unable to find that sponsorship.'));
        }
        $em = $this->get('doctrine')->getManager();
        $post = json_decode($request->getContent());

        $djRead = new DJReadShowSponsorship();

        $djRead->setDjInitials($post->dj_initials);
        $djRead->setShowSponsorship($sponsorship);
        $em->persist($djRead);
        $em->flush();
        return new JsonResponse(array('status' => 'success'));
    }

    public function psaReadItAction(Request $request, AdPSA $psa)
    {
        if (!$psa) {
            return new JsonResponse(array('status' => 'error', 'message' => 'Unable to find that psa.'));
        }
        $em = $this->get('doctrine')->getManager();
        $post = json_decode($request->getContent());

        $djRead = new DJReadPSA();

        $djRead->setDjInitials($post->dj_initials);
        $djRead->setPsa($psa);
        $em->persist($djRead);
        $em->flush();

        $psa->setLastRead(new \Datetime());
        $em->persist($psa);
        $em->flush();
        return new JsonResponse(array('status' => 'success'));
    }

    public function psaSkipAction(Request $request, AdPSA $psa)
    {
        if (!$psa) {
            return new JsonResponse(array('status' => 'error', 'message' => 'Unable to find that psa.'));
        }
        $em = $this->get('doctrine')->getManager();

        // Just set the last read time to now
        // this is a bit hacky but works fine        
        $psa->setLastRead(new \Datetime());
        $em->persist($psa);
        $em->flush();
        return new JsonResponse(array('status' => 'success'));
    }
}
<?php

namespace DJBlaster\BlasterBundle\Entity;

use \Datetime;
use Doctrine\ORM\EntityRepository;


/**
 * AdShowSponsorshipRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdShowSponsorshipRepository extends EntityRepository
{
    public function findAllOrderedByNameForCustomerAndCampaign(Customer $customer, CustomerCampaign $campaign)
    {
        return $this->findBy(array('customer' => $customer, 'campaign' => $campaign), array('ad_name' => 'ASC'));
    }

    public function findAllOrderedByNameForCampaign(CustomerCampaign $campaign)
    {
        return $this->findBy(array('campaign' => $campaign), array('ad_name' => 'ASC'));
    }

    public function findAllForHour($hour, $time)
    {
        /**
         * REPLACED NEXT BLOCK WITH TWO FUNCTIONS
         * 
         * BUG WAS IN THE CALCULATION OF THE WEEK
         * 
         */
        // Why 6am? Because for some reason it reads Jan 01 as the prior year based on timezone
        //$startDateTime = DateTime::createFromFormat("Y-m-d H", date("Y-m-01 10", $time));
        //$startWeek = intval($startDateTime->format('W'));
        // $currentDateTime = new DateTime();
        // $currentDateTime->setTimestamp($time);
        // $currentWeek = $currentDateTime->format("W");
        //$weekNum = intval($currentWeek) - $startWeek + 1;
        $weekNum = $this->getWeekOfMonth($time);

        // Fix the odd case where the week is 5 or *possibly* 6
        switch ($weekNum) {
            case 5:
                $weekNum = 1;
                break;
            case 6:
                $weekNum = 2;
        }

        $weekColumn = "days_week" . $weekNum;
        $currentDay = date("D", $time);
        $currentDate = date("Y-m-d", $time);

        $currentHour = $hour . ":00:00";

        $fields = array('s.sponsorship_id, s.ad_name, s.ad_content');
        $qb = $this->createQueryBuilder('s');
        $query = $qb->select($fields)
            ->innerJoin('DJBlasterBundle:CustomerCampaign', 'c', 'WITH', 'c.campaign_id = s.campaign')
            ->where("c.start_date <= :currentDate")
            ->andWhere("c.end_date >= :currentDate")
            ->andWhere("s.begin_time <= :currentHour")
            ->andWhere("s.end_time > :currentHour")
            ->andWhere("s." . $weekColumn . " LIKE :currentDay")
            ->setParameters(array(
                'currentDate' => $currentDate,
                'currentHour' => $currentHour,
                'currentDay' => "%" . $currentDay . "%",
            ))

            ->getQuery();

        $result =  $query->getResult();
        // print_r($result);
        // echo $query->getSql();
        // var_dump($query->getParameters());
        // die;
        return $result;
    }

    // From: https://stackoverflow.com/questions/32615861/get-week-number-in-month-from-date-in-php/32624747
    private function getWeekOfMonth($date)
    {
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date) . " 8am");
        //Apply above formula.
        return $this->getWeekOfYear($date) - $this->getWeekOfYear($firstOfMonth) + 1;
    }

    // From: https://stackoverflow.com/questions/32615861/get-week-number-in-month-from-date-in-php/32624747
    private function getWeekOfYear($date)
    {
        $weekOfYear = intval(strftime("%U", $date));
        if (date('n', $date) == "1" && $weekOfYear > 51) {
            // It's the last week of the previos year.
            return 0;
        } else if (date('n', $date) == "12" && $weekOfYear == 1) {
            // It's the first week of the next year.
            return 53;
        } else {
            // It's a "normal" week.
            return $weekOfYear;
        }
    }
}

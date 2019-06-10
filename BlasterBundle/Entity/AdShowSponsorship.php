<?php

namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_ad_show_sponsorships")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\AdShowSponsorshipRepository")
 */
class AdShowSponsorship
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $sponsorship_id;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\CustomerCampaign", inversedBy="adShowSponsorships")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="campaign_id")
     */
    protected $campaign;

    /**
     * @ORM\OneToMany(targetEntity="DJBlaster\BlasterBundle\Entity\DJReadShowSponsorship", mappedBy="showSponsorship", cascade={"remove"}, orphanRemoval=true)
     */
    protected $djReadShowSponsorships = null;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="You must include the sponsorship title.")
     */
    protected $ad_name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="You must include some sponsorship text.")
     */
    protected $ad_content = "";

    /**
     * @ORM\Column(type="time")
     */
    protected $begin_time = "8:00:00";

    /**
     * @ORM\Column(type="time")
     */
    protected $end_time = "9:00:00";

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $days_week1 = "";

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $days_week2 = "";

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $days_week3 = "";

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $days_week4 = "";

    /**
     * @Assert\IsTrue(message="You must select at least one day for the show.")
     */
    public function isDaysWeek()
    {
        // Make sure at least one day is selected
        $not_blank = false;
        if ($this->days_week1 != null && $this->days_week1 != "") {
            $not_blank = true;
        }
        if ($this->days_week2 != null && $this->days_week2 != "") {
            $not_blank = true;
        }
        if ($this->days_week3 != null && $this->days_week3 != "") {
            $not_blank = true;
        }
        if ($this->days_week4 != null && $this->days_week4 != "") {
            $not_blank = true;
        }
        return $not_blank;
    }

    public function __construct()
    {
        $this->customer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->campaign = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCampaign(CustomerCampaign $campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Get sponsorship_id
     *
     * @return integer 
     */
    public function getSponsorshipId()
    {
        return $this->sponsorship_id;
    }

    /**
     * Set ad_name
     *
     * @param string $adName
     * @return AdShowSponsorship
     */
    public function setAdName($adName)
    {
        $this->ad_name = $adName;

        return $this;
    }

    /**
     * Get ad_name
     *
     * @return string 
     */
    public function getAdName()
    {
        return $this->ad_name;
    }

    /**
     * Set ad_content
     *
     * @param string $adContent
     * @return AdShowSponsorship
     */
    public function setAdContent($adContent)
    {
        $this->ad_content = $adContent;

        return $this;
    }

    /**
     * Get ad_content
     *
     * @return string 
     */
    public function getAdContent()
    {
        return $this->ad_content;
    }

    /**
     * Set days_week1
     *
     * @param string $daysWeek1
     * @return AdShowSponsorship
     */
    public function setDaysWeek1($daysWeek1)
    {
        $this->days_week1 = $daysWeek1;

        return $this;
    }

    /**
     * Get days_week1
     *
     * @return string 
     */
    public function getDaysWeek1()
    {
        return $this->days_week1;
    }

    /**
     * Set days_week2
     *
     * @param string $daysWeek2
     * @return AdShowSponsorship
     */
    public function setDaysWeek2($daysWeek2)
    {
        $this->days_week2 = $daysWeek2;

        return $this;
    }

    /**
     * Get days_week2
     *
     * @return string 
     */
    public function getDaysWeek2()
    {
        return $this->days_week2;
    }

    /**
     * Set days_week3
     *
     * @param string $daysWeek3
     * @return AdShowSponsorship
     */
    public function setDaysWeek3($daysWeek3)
    {
        $this->days_week3 = $daysWeek3;

        return $this;
    }

    /**
     * Get days_week3
     *
     * @return string 
     */
    public function getDaysWeek3()
    {
        return $this->days_week3;
    }

    /**
     * Set days_week4
     *
     * @param string $daysWeek4
     * @return AdShowSponsorship
     */
    public function setDaysWeek4($daysWeek4)
    {
        $this->days_week4 = $daysWeek4;

        return $this;
    }

    /**
     * Get days_week4
     *
     * @return string 
     */
    public function getDaysWeek4()
    {
        return $this->days_week4;
    }

    /**
     * Set begin_time
     *
     * @param \DateTime $beginTime
     * @return AdShowSponsorship
     */
    public function setBeginTime($beginTime)
    {
        $this->begin_time = $beginTime;

        return $this;
    }

    /**
     * Get begin_time
     *
     * @return \DateTime 
     */
    public function getBeginTime()
    {
        return $this->begin_time;
    }

    /**
     * Set end_time
     *
     * @param \DateTime $endTime
     * @return AdShowSponsorship
     */
    public function setEndTime($endTime)
    {
        $this->end_time = $endTime;

        return $this;
    }

    /**
     * Get end_time
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->end_time;
    }



    /**
     * Set show_persist_hours
     *
     * @param string $showPersistHours
     * @return AdShowSponsorship
     */
    public function setShowPersistHours($showPersistHours)
    {
        $this->show_persist_hours = $showPersistHours;

        return $this;
    }

    /**
     * Get show_persist_hours
     *
     * @return string 
     */
    public function getShowPersistHours()
    {
        return $this->show_persist_hours;
    }

    /**
     * Add djReadShowSponsorships
     *
     * @param \DJBlaster\BlasterBundle\Entity\DJReadShowSponsorship $djReadShowSponsorships
     * @return AdShowSponsorship
     */
    public function addDjReadShowSponsorship(\DJBlaster\BlasterBundle\Entity\DJReadShowSponsorship $djReadShowSponsorships)
    {
        $this->djReadShowSponsorships[] = $djReadShowSponsorships;

        return $this;
    }

    /**
     * Remove djReadShowSponsorships
     *
     * @param \DJBlaster\BlasterBundle\Entity\DJReadShowSponsorship $djReadShowSponsorships
     */
    public function removeDjReadShowSponsorship(\DJBlaster\BlasterBundle\Entity\DJReadShowSponsorship $djReadShowSponsorships)
    {
        $this->djReadShowSponsorships->removeElement($djReadShowSponsorships);
    }

    /**
     * Get djReadShowSponsorships
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDjReadShowSponsorships()
    {
        return $this->djReadShowSponsorships;
    }
}
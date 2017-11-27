<?php

namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_ad_events")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\AdEventRepository")
 */
class AdEvent {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $event_id;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;
    
    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\CustomerCampaign", inversedBy="adEvents")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="campaign_id")
     */
    protected $campaign;
    
    /**
     * @ORM\OneToMany(targetEntity="DJBlaster\BlasterBundle\Entity\DJReadEvent", mappedBy="event", cascade={"remove"}, orphanRemoval=true)
     */
    protected $djReadEvents = null;
    
    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="You must include the ad name.")
     */
    protected $ad_name;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="You must include some text for this event.")
     */
    protected $ad_content;
    
    /**
     * @ORM\Column(type="date")
     * @Assert\Date(message="Start date must be valid.")
     */
    protected $start_date;
    
    /**
     * @ORM\Column(type="date")
     * @Assert\Date(message="End date must be valid.")
     */
    protected $end_date;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="You must include a number of times to read this event.")
     * @Assert\Type(type="numeric", message="The value {{ value }} is not a valid number.")
     * @Assert\GreaterThan(value=0, message="The number of reads must be greater than zero.")
     */
    protected $no_reads;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $no_reads_per_day = 0;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $no_reads_remaining_today = 0;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $no_reads_performed = 0;
    
    /**
     * @ORM\Column(type="time")
     */
    protected $next_read_time;
    
     /**
     * @ORM\Column(type="datetime")
     */
    protected $last_read;
    
    /**
     * @ORM\Column(type="date")
     */
    protected $last_read_date;
    
   

    /**
     * @Assert\IsTrue(message="The end date must occur after the start date.")
     */
    public function isDatesValid(){
        return ($this->start_date <= $this->end_date);
    }

    public function __construct() {
        $this->customer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->campaign = new \Doctrine\Common\Collections\ArrayCollection();
        
        $this->start_date = new \DateTime();
        $this->end_date = new \DateTime();
        
        $this->last_read = new \DateTime();
        $this->last_read->setTimestamp(0);
        
        $this->last_read_date = new \DateTime();
        $this->last_read_date->setTimestamp(0);

        $this->next_read_time = new \DateTime();
        $this->next_read_time->setTimestamp(0);
    }

    public function setCustomer(Customer $customer) {
        $this->customer = $customer;
    }

    public function getCustomer() {
        return $this->customer;
    }
    
    public function setCampaign(CustomerCampaign $campaign) {
        $this->campaign = $campaign;
    }

    public function getCampaign() {
        return $this->campaign;
    }

    
    /**
     * Set ad_name
     *
     * @param string $adName
     * @return AdEvents
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
     * @return AdEvents
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
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return AdEvents
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->start_date;
    }
    

    /**
     * Set end_date
     *
     * @param \DateTime $endDate
     * @return AdEvents
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set no_reads
     *
     * @param integer $noReads
     * @return AdEvents
     */
    public function setNoReads($noReads)
    {
        $this->no_reads = $noReads;

        return $this;
    }

    /**
     * Get no_reads
     *
     * @return integer 
     */
    public function getNoReads()
    {
        return $this->no_reads;
    }

    
    /**
     * Get event_id
     *
     * @return integer 
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Set no_reads_per_day
     *
     * @param integer $noReadsPerDay
     * @return AdEvent
     */
    public function setNoReadsPerDay($noReadsPerDay)
    {
        $this->no_reads_per_day = $noReadsPerDay;

        return $this;
    }

    /**
     * Get no_reads_per_day
     *
     * @return integer 
     */
    public function getNoReadsPerDay()
    {
        return $this->no_reads_per_day;
    }

    /**
     * Add djReadEvents
     *
     * @param \DJBlaster\BlasterBundle\Entity\DJReadEvent $djReadEvents
     * @return AdEvent
     */
    public function addDjReadEvent(\DJBlaster\BlasterBundle\Entity\DJReadEvent $djReadEvents)
    {
        $this->djReadEvents[] = $djReadEvents;

        return $this;
    }

    /**
     * Remove djReadEvents
     *
     * @param \DJBlaster\BlasterBundle\Entity\DJReadEvent $djReadEvents
     */
    public function removeDjReadEvent(\DJBlaster\BlasterBundle\Entity\DJReadEvent $djReadEvents)
    {
        $this->djReadEvents->removeElement($djReadEvents);
    }

    /**
     * Get djReadEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDjReadEvents()
    {
        return $this->djReadEvents;
    }



    /**
     * Set no_reads_performed
     *
     * @param integer $noReadsPerformed
     * @return AdEvent
     */
    public function setNoReadsPerformed($noReadsPerformed)
    {
        $this->no_reads_performed = $noReadsPerformed;

        return $this;
    }

    /**
     * Get no_reads_performed
     *
     * @return integer 
     */
    public function getNoReadsPerformed()
    {
        return $this->no_reads_performed;
    }

    /**
     * Set no_reads_remaining_today
     *
     * @param integer $noReadsRemainingToday
     * @return AdEvent
     */
    public function setNoReadsRemainingToday($noReadsRemainingToday)
    {
        $this->no_reads_remaining_today = $noReadsRemainingToday;

        return $this;
    }

    /**
     * Get no_reads_remaining_today
     *
     * @return integer 
     */
    public function getNoReadsRemainingToday()
    {
        return $this->no_reads_remaining_today;
    }

    /**
     * Set last_read
     *
     * @param \DateTime $lastRead
     * @return AdEvent
     */
    public function setLastRead($lastRead)
    {
        $this->last_read = $lastRead;

        return $this;
    }

    /**
     * Get last_read
     *
     * @return \DateTime 
     */
    public function getLastRead()
    {
        return $this->last_read;
    }

    /**
     * Set last_read_date
     *
     * @param \DateTime $lastReadDate
     * @return AdEvent
     */
    public function setLastReadDate($lastReadDate)
    {
        $this->last_read_date = $lastReadDate;

        return $this;
    }

    /**
     * Get last_read_date
     *
     * @return \DateTime 
     */
    public function getLastReadDate()
    {
        return $this->last_read_date;
    }

    /**
     * Set next_read_time
     *
     * @param \DateTime $nextReadTime
     * @return AdEvent
     */
    public function setNextReadTime($nextReadTime)
    {
        $this->next_read_time = $nextReadTime;

        return $this;
    }

    /**
     * Get next_read_time
     *
     * @return \DateTime 
     */
    public function getNextReadTime()
    {
        return $this->next_read_time;
    }
}

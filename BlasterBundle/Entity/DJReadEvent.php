<?php

namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use DJBlaster\BlasterBundle\Entity\AdEvent;

/**
 * @ORM\Entity
 * @ORM\Table(name="dj_read_events")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\DJReadEventRepository")
 */
class DJReadEvent {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $read_id;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\AdEvent", inversedBy="djReadEvents")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     */
    protected $event;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $dj_initials;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $time_read;
    
    public function __construct()
    {
        $this->event = new \Doctrine\Common\Collections\ArrayCollection();
        $this->time_read = new \DateTime();
    }
    
    public function setEvent(AdEvent $event) {
        $this->event = $event;
        
        return $this;
    }

    public function getEvent() {
        return $this->event;
    }

    /**
     * Get read_id
     *
     * @return integer 
     */
    public function getReadId()
    {
        return $this->read_id;
    }

    /**
     * Set dj_initials
     *
     * @param string $djInitials
     * @return DJReadEvent
     */
    public function setDjInitials($djInitials)
    {
        $this->dj_initials = $djInitials;

        return $this;
    }

    /**
     * Get dj_initials
     *
     * @return string 
     */
    public function getDjInitials()
    {
        return $this->dj_initials;
    }

    /**
     * Set time_read
     *
     * @param \DateTime $timeRead
     * @return DJReadEvent
     */
    public function setTimeRead($timeRead)
    {
        $this->time_read = $timeRead;

        return $this;
    }

    /**
     * Get time_read
     *
     * @return \DateTime 
     */
    public function getTimeRead()
    {
        return $this->time_read;
    }
}

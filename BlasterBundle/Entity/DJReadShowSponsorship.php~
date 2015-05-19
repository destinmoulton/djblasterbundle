<?php

namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use DJBlaster\BlasterBundle\Entity\AdShowSponsorship;

/**
 * @ORM\Entity
 * @ORM\Table(name="dj_read_show_sponsorships")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\DJReadShowSponsorshipRepository")
 */
class DJReadShowSponsorship {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $read_id;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\AdShowSponsorship", inversedBy="djReadShowSponsorships")
     * @ORM\JoinColumn(name="sponsorship_id", referencedColumnName="sponsorship_id")
     */
    protected $showSponsorship;
    
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
        $this->showSponsorship = new \Doctrine\Common\Collections\ArrayCollection();
        $this->time_read = new \DateTime();
    }
    
    public function setShowSponsorship(AdShowSponsorship $sponsorship) {
        $this->showSponsorship = $sponsorship;
        
        return $this;
    }

    public function getShowSponsorship() {
        return $this->showSponsorship;
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
     * @return DJReadShowSponsorship
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
     * @return DJReadShowSponsorship
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

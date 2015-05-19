<?php

namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use DJBlaster\BlasterBundle\Entity\AdPSA;

/**
 * @ORM\Entity
 * @ORM\Table(name="dj_read_psas")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\DJReadPSARepository")
 */
class DJReadPSA {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $read_id;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\AdPSA", inversedBy="djReadPSAs")
     * @ORM\JoinColumn(name="psa_id", referencedColumnName="psa_id")
     */
    protected $psa;
    
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
        $this->psa = new \Doctrine\Common\Collections\ArrayCollection();
        $this->time_read = new \DateTime();
    }
    
    public function setPsa(AdPSA $psa) {
        $this->psa = $psa;
        
        return $this;
    }

    public function getPsa() {
        return $this->psa;
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
     * @return DJReadPSA
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
     * @return DJReadPSA
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

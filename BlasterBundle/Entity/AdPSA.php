<?php

namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Entity\CustomerCampaign;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_ad_psas")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\AdPSARepository")
 */
class AdPSA
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $psa_id;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\CustomerCampaign", inversedBy="adPSAs")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="campaign_id")
     */
    protected $campaign;

    /**
     * @ORM\OneToMany(targetEntity="DJBlaster\BlasterBundle\Entity\DJReadPSA", mappedBy="psa", cascade={"remove"}, orphanRemoval=true)
     */
    protected $djReadPSAs = null;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="You must include the PSA title.")
     */
    protected $ad_name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="You must include some text for the PSA.")
     */
    protected $ad_content;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $last_read;


    public function __construct()
    {
        $this->customer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->campaign = new \Doctrine\Common\Collections\ArrayCollection();

        $this->last_read = new \DateTime();
        $this->last_read->setTimestamp(0);
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCampaign(CustomerCampaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Get psa_id
     *
     * @return integer 
     */
    public function getPsaId()
    {
        return $this->psa_id;
    }

    /**
     * Set ad_name
     *
     * @param string $adName
     * @return AdPSA
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
     * @return AdPSA
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
     * Set last_read
     *
     * @param \DateTime $lastRead
     * @return AdPSA
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
     * Add djReadPSAs
     *
     * @param \DJBlaster\BlasterBundle\Entity\DJReadPSA $djReadPSAs
     * @return AdPSA
     */
    public function addDjReadPSA(\DJBlaster\BlasterBundle\Entity\DJReadPSA $djReadPSAs)
    {
        $this->djReadPSAs[] = $djReadPSAs;

        return $this;
    }

    /**
     * Remove djReadPSAs
     *
     * @param \DJBlaster\BlasterBundle\Entity\DJReadPSA $djReadPSAs
     */
    public function removeDjReadPSA(\DJBlaster\BlasterBundle\Entity\DJReadPSA $djReadPSAs)
    {
        $this->djReadPSAs->removeElement($djReadPSAs);
    }

    /**
     * Get djReadPSAs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDjReadPSAs()
    {
        return $this->djReadPSAs;
    }
}
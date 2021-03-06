<?php
// src/DJBlaster/BlasterBundle/Entity/CustomerCampaign.php
namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use DJBlaster\BlasterBundle\Entity\Customer;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_campaigns")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\CustomerCampaignRepository")
 */
class CustomerCampaign
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $campaign_id;

    /**
     * @ORM\ManyToOne(targetEntity="DJBlaster\BlasterBundle\Entity\Customer", inversedBy="campaigns")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="DJBlaster\BlasterBundle\Entity\AdShowSponsorship", mappedBy="campaign", cascade={"remove"}, orphanRemoval=true)
     */
    protected $adShowSponsorships = null;

    /**
     * @ORM\OneToMany(targetEntity="DJBlaster\BlasterBundle\Entity\AdEvent", mappedBy="campaign", cascade={"remove"}, orphanRemoval=true)
     */
    protected $adEvents = null;

    /**
     * @ORM\OneToMany(targetEntity="DJBlaster\BlasterBundle\Entity\AdPSA", mappedBy="campaign", cascade={"remove"}, orphanRemoval=true)
     */
    protected $adPSAs = null;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="You must include the campaign name.")
     */
    protected $campaign_name;

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
     * @Assert\IsTrue(message="The end date must occur after the start date.")
     */
    public function isDatesValid()
    {
        return ($this->start_date <= $this->end_date);
    }

    public function __construct()
    {
        $this->customer = new \Doctrine\Common\Collections\ArrayCollection();

        $this->start_date = new \DateTime();
        $this->end_date = new \DateTime();
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }



    /**
     * Get campaign_id
     *
     * @return integer
     */
    public function getCampaignId()
    {
        return $this->campaign_id;
    }

    /**
     * Set campaign_name
     *
     * @param string $campaignName
     * @return CustomerCampaign
     */
    public function setCampaignName($campaignName)
    {
        $this->campaign_name = $campaignName;

        return $this;
    }

    /**
     * Get campaign_name
     *
     * @return string
     */
    public function getCampaignName()
    {
        return $this->campaign_name;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return CustomerCampaign
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
     * @return CustomerCampaign
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
     * Add adShowSponsorships
     *
     * @param \DJBlaster\BlasterBundle\Entity\AdShowSponsorship $adShowSponsorships
     * @return CustomerCampaign
     */
    public function addAdShowSponsorship(\DJBlaster\BlasterBundle\Entity\AdShowSponsorship $adShowSponsorships)
    {
        $this->adShowSponsorships[] = $adShowSponsorships;

        return $this;
    }

    /**
     * Remove adShowSponsorships
     *
     * @param \DJBlaster\BlasterBundle\Entity\AdShowSponsorship $adShowSponsorships
     */
    public function removeAdShowSponsorship(\DJBlaster\BlasterBundle\Entity\AdShowSponsorship $adShowSponsorships)
    {
        $this->adShowSponsorships->removeElement($adShowSponsorships);
    }

    /**
     * Get adShowSponsorships
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdShowSponsorships()
    {
        return $this->adShowSponsorships;
    }

    /**
     * Add adEvents
     *
     * @param \DJBlaster\BlasterBundle\Entity\AdEvent $adEvents
     * @return CustomerCampaign
     */
    public function addAdEvent(\DJBlaster\BlasterBundle\Entity\AdEvent $adEvents)
    {
        $this->adEvents[] = $adEvents;

        return $this;
    }

    /**
     * Remove adEvents
     *
     * @param \DJBlaster\BlasterBundle\Entity\AdEvent $adEvents
     */
    public function removeAdEvent(\DJBlaster\BlasterBundle\Entity\AdEvent $adEvents)
    {
        $this->adEvents->removeElement($adEvents);
    }

    /**
     * Get adEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdEvents()
    {
        return $this->adEvents;
    }

    /**
     * Add adPSAs
     *
     * @param \DJBlaster\BlasterBundle\Entity\AdPSA $adPSAs
     * @return CustomerCampaign
     */
    public function addAdPSA(\DJBlaster\BlasterBundle\Entity\AdPSA $adPSAs)
    {
        $this->adPSAs[] = $adPSAs;

        return $this;
    }

    /**
     * Remove adPSAs
     *
     * @param \DJBlaster\BlasterBundle\Entity\AdPSA $adPSAs
     */
    public function removeAdPSA(\DJBlaster\BlasterBundle\Entity\AdPSA $adPSAs)
    {
        $this->adPSAs->removeElement($adPSAs);
    }

    /**
     * Get adPSAs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdPSAs()
    {
        return $this->adPSAs;
    }
}
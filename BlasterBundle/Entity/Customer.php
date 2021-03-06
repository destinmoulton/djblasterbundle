<?php
// src/DJBlaster/BlasterBundle/Entity/Customer.php
namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="customers")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\OneToMany(targetEntity="DJBlaster\BlasterBundle\Entity\CustomerCampaign", mappedBy="customer", cascade={"remove"}, orphanRemoval=true)
     */
    protected $campaigns;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="You must include the customer name.")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    protected $info;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set info
     *
     * @param string $info
     * @return Customer
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->campaigns = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add campaigns
     *
     * @param \DJBlaster\BlasterBundle\Entity\CustomerCampaign $campaigns
     * @return Customer
     */
    public function addCampaign(\DJBlaster\BlasterBundle\Entity\CustomerCampaign $campaigns)
    {
        $this->campaigns[] = $campaigns;

        return $this;
    }

    /**
     * Remove campaigns
     *
     * @param \DJBlaster\BlasterBundle\Entity\CustomerCampaign $campaigns
     */
    public function removeCampaign(\DJBlaster\BlasterBundle\Entity\CustomerCampaign $campaigns)
    {
        $this->campaigns->removeElement($campaigns);
    }

    /**
     * Get campaigns
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }
}
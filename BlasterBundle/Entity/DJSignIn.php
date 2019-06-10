<?php

namespace DJBlaster\BlasterBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dj_signin")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\DJSignInRepository")
 */
class DJSignIn
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $signin_id;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank(message="You must include the show title.")
     */
    protected $show_title;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="You must include the start time.")
     */
    protected $show_start_time;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message="You must include the end time.")
     */
    protected $show_end_time;

    /**
     * @ORM\Column(type="string", length=75)
     * @Assert\NotBlank(message="You must include your first name.")
     */
    protected $dj_first_name;

    /**
     * @ORM\Column(type="string", length=75)
     * @Assert\NotBlank(message="You must include your last name.")
     */
    protected $dj_last_name;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank(message="You must include an email address.")
     * @Assert\Email()p
     */
    protected $dj_email;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $signin_datetime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $signout_datetime;

    public function __construct()
    {
        $this->signin_datetime = new \DateTime();
    }



    /**
     * Get signin_id
     *
     * @return integer 
     */
    public function getSigninId()
    {
        return $this->signin_id;
    }

    /**
     * Set show_title
     *
     * @param string $showTitle
     * @return DJSignIn
     */
    public function setShowTitle($showTitle)
    {
        $this->show_title = $showTitle;

        return $this;
    }

    /**
     * Get show_title
     *
     * @return string 
     */
    public function getShowTitle()
    {
        return $this->show_title;
    }

    /**
     * Set show_start_time
     *
     * @param \DateTime $showStartTime
     * @return DJSignIn
     */
    public function setShowStartTime($showStartTime)
    {
        $this->show_start_time = $showStartTime;

        return $this;
    }

    /**
     * Get show_start_time
     *
     * @return \DateTime 
     */
    public function getShowStartTime()
    {
        return $this->show_start_time;
    }

    /**
     * Set show_end_time
     *
     * @param \DateTime $showEndTime
     * @return DJSignIn
     */
    public function setShowEndTime($showEndTime)
    {
        $this->show_end_time = $showEndTime;

        return $this;
    }

    /**
     * Get show_end_time
     *
     * @return \DateTime 
     */
    public function getShowEndTime()
    {
        return $this->show_end_time;
    }

    /**
     * Set dj_first_name
     *
     * @param string $djFirstName
     * @return DJSignIn
     */
    public function setDjFirstName($djFirstName)
    {
        $this->dj_first_name = $djFirstName;

        return $this;
    }

    /**
     * Get dj_first_name
     *
     * @return string 
     */
    public function getDjFirstName()
    {
        return $this->dj_first_name;
    }

    /**
     * Set dj_last_name
     *
     * @param string $djLastName
     * @return DJSignIn
     */
    public function setDjLastName($djLastName)
    {
        $this->dj_last_name = $djLastName;

        return $this;
    }

    /**
     * Get dj_last_name
     *
     * @return string 
     */
    public function getDjLastName()
    {
        return $this->dj_last_name;
    }

    /**
     * Set dj_email
     *
     * @param string $djEmail
     * @return DJSignIn
     */
    public function setDjEmail($djEmail)
    {
        $this->dj_email = $djEmail;

        return $this;
    }

    /**
     * Get dj_email
     *
     * @return string 
     */
    public function getDjEmail()
    {
        return $this->dj_email;
    }

    /**
     * Set signin_datetime
     *
     * @param \DateTime $signinDatetime
     * @return DJSignIn
     */
    public function setSigninDatetime($signinDatetime)
    {
        $this->signin_datetime = $signinDatetime;

        return $this;
    }

    /**
     * Get signin_datetime
     *
     * @return \DateTime 
     */
    public function getSigninDatetime()
    {
        return $this->signin_datetime;
    }

    /**
     * Set signout_datetime
     *
     * @param \DateTime $signoutDatetime
     * @return DJSignIn
     */
    public function setSignoutDatetime($signoutDatetime)
    {
        $this->signout_datetime = $signoutDatetime;

        return $this;
    }

    /**
     * Get signout_datetime
     *
     * @return \DateTime 
     */
    public function getSignoutDatetime()
    {
        return $this->signout_datetime;
    }
}
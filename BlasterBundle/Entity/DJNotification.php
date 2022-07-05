<?php
/**
 * Notification Entity for the DJ Sign In Page
 *
 * These are notifications for:
 *  - Above DJ sign in form
 *  - After dj login
 *
 * Includes:
 *   - Start and End Dates
 *   - Color
 *   - Text Block
 *   - Button text (when applicable)
 *
 * Add the notification using phpmyadmin.
 *
 * NOTE: Title is NOT editable (use phpmyadmin)
 */
namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="dj_notifications")
 */
class DJNotification {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $notification_id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $title;

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
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="You must include some text for the notification.")
     */
    protected $message;

    /**
     * @ORM\Column(type="string", length=7)
     */
    protected $primary_color_hex;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $button_text;

    /**
     * @Assert\IsTrue(message="The end date must occur after the start date.")
     */
    public function isDatesValid()
    {
        return ($this->start_date <= $this->end_date);
    }

    public function __construct()
    {
        $this->start_date = new \DateTime();
        $this->end_date = new \DateTime();
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get notification id
     *
     * @return string
     */
    public function getNotificationId()
    {
        return $this->notification_id;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return DJNotification
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
     * @return DJNotification
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
     * Set message
     *
     * @param string $message
     * @return DJNotification
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set color hex
     *
     * @param string $color
     * @return DJNotification
     */
    public function setPrimaryColorHex($color)
    {
        $this->primary_color_hex = $color;
        return $this;
    }

    /**
     * Get color hex
     *
     * @return string
     */
    public function getPrimaryColorHex()
    {
        return $this->primary_color_hex;
    }

    /**
     * Set button text
     *
     * @param string $buttonText
     * @return DJNotification
     */
    public function setButtonText($buttonText)
    {
        $this->button_text = $buttonText;
        return $this;
    }

    /**
     * Get button text
     *
     * @return string
     */
    public function getButtonText()
    {
        return $this->button_text;
    }
}
<?php
// src/DJBlaster/BlasterBundle/User.php
namespace DJBlaster\BlasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="DJBlaster\BlasterBundle\Entity\UserRepository")
 * @UniqueEntity("email", message="This email address is already used. The user can reset their password from the login page.")
 */
class User implements UserInterface, \Serializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $roles = "ROLE_ADMIN";

    /**
     * @ORM\Column(type="string", length=35)
     * @Assert\NotBlank(message="You must include a name.")
     * @Assert\Length(min=3, minMessage = "The name must be at least {{ limit }} characters long.")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="You must include an email address.")
     * @Assert\Email()
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=172)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=23)
     */
    protected $salt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Users
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        // username for these users is actually their email address
        return $this->email;
    }

    public function getRoles() {
        return array('ROLE_ADMIN');
    }

    public function eraseCredentials() {
    }

    /** @see \Serializable::serialize() */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->name,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) {
        list($this->id, $this->name, $this->password,
        // see section on salt below
        // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return User
     */
    public function setRoles($roles) {
        $this->roles = $roles;

        return $this;
    }

}

<?php

namespace DJBlaster\BlasterBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(message="The current password is incorrect.")
     */
    protected $currentPassword;

    /**
     * @Assert\NotBLank(message="You must include a new password.")
     * @Assert\Length(min=7, minMessage="New password should be at least {{ limit }} chars long.")
     */
    protected $newPassword;

    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword($password)
    {
        $this->currentPassword = $password;
        return $this;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setNewPassword($password)
    {
        $this->newPassword = $password;
        return $this;
    }
}
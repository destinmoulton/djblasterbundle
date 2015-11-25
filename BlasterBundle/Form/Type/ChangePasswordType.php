<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePasswordType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('currentPassword', 'password');
        $builder->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'first_options' => array('label' => 'New Password'),
            'second_options' => array('label' => 'Confirm Password'),
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array('data_class' => 'DJBlaster\BlasterBundle\Form\Model\ChangePassword', ));
    }

    public function getName() {
        return 'change_password';
    }

}

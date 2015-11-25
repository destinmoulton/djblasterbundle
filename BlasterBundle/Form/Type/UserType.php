<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder -> add('name') -> add('email') -> add('save', 'submit');

    }

    public function getName() {
        return 'user';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver -> setDefaults(array('data_class' => 'DJBlaster\BlasterBundle\Entity\User', 'cascade_validation' => true, ));
    }
}
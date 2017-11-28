<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder -> add('name') -> add('email') -> add('save', SubmitType::class);
    }

    public function getBlockPrefix() {
        return 'user';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver -> setDefaults(array('data_class' => 'DJBlaster\BlasterBundle\Entity\User', 'cascade_validation' => true, ));
    }
}
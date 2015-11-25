<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use DJBlaster\BlasterBundle\Form\DataTransformer\DateTimeToStringTransformer;

class DJSignInType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('show_title', 'text', array('label'=>'Show Title'));
        $builder->add(
            $builder->create('show_start_time', 'text', array('label'=>'Show Time'))
                    ->addModelTransformer(new DateTimeToStringTransformer()));
        $builder->add($builder->create('show_end_time', 'text', array('label'=>'Show End Time'))
                    ->addModelTransformer(new DateTimeToStringTransformer()));
        $builder->add('dj_first_name', 'text', array('label'=>'DJ First Name'));
        $builder->add('dj_last_name', 'text', array('label'=>'DJ Last Name'));
        $builder->add('dj_email', 'text', array('label'=>'DJ Email Address'));
        $builder->add('signin', 'submit', array('label'=>'Sign In'));

    }

    public function getName() {
        return 'djsignin';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver -> setDefaults(array('data_class' => 'DJBlaster\BlasterBundle\Entity\DJSignIn', 'cascade_validation' => true, ));
    }

}

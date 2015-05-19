<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdPSAType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('ad_name', 'text', array('label'=>'Title'));
        $builder->add('ad_content', 'textarea', array('label'=>'PSA Text'));
        
    }

    public function getName() {
        return 'customer_ad_psa';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'DJBlaster\BlasterBundle\Entity\AdPSA',
            'cascade_validation' => true,
        ));
    }

}
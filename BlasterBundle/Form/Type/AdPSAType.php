<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdPSAType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('ad_name', TextType::class, array('label'=>'Title'));
        $builder->add('ad_content', TextareaType::class, array('label'=>'PSA Text'));
        
    }

    public function getBlockPrefix() {
        return 'customer_ad_psa';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'DJBlaster\BlasterBundle\Entity\AdPSA',
            'cascade_validation' => true,
        ));
    }

}
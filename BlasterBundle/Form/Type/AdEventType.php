<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AdEventType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('ad_name', TextType::class, array('label'=>'Title'));
        $builder->add('ad_content', TextareaType::class, array('label'=>'Promo Text'));
        
        $builder->add('start_date', DateType::class, array('label'=>'Event Start Date'));
        $builder->add('end_date', DateType::class, array('label'=>'Event End Date'));
        
        $builder->add('no_reads', TextType::class, array('label'=>'Number of Reads'));
        
    }

    public function getBlockPrefix() {
        return 'customer_ad_event';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'DJBlaster\BlasterBundle\Entity\AdEvent',
            'cascade_validation' => true,
        ));
    }

}
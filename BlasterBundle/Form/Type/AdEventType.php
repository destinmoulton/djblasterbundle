<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdEventType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('ad_name', 'text', array('label'=>'Title'));
        $builder->add('ad_content', 'textarea', array('label'=>'Event Text'));
        
        $builder->add('start_date', 'date', array('label'=>'Event Start Date'));
        $builder->add('end_date', 'date', array('label'=>'Event End Date'));
        
        $builder->add('no_reads', 'text', array('label'=>'Number of Reads'));
        
    }

    public function getName() {
        return 'customer_ad_event';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'DJBlaster\BlasterBundle\Entity\AdEvent',
            'cascade_validation' => true,
        ));
    }

}
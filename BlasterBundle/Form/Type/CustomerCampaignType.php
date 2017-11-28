<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CustomerCampaignType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('campaign_name', TextType::class);
        $builder->add('start_date', DateType::class);
        $builder->add('end_date', DateType::class);
    }

    public function getBlockPrefix() {
        return 'customer_campaign';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'DJBlaster\BlasterBundle\Entity\CustomerCampaign',
            'cascade_validation' => true,
        ));
    }

}

<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerCampaignType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('campaign_name', 'text');
        $builder->add('start_date', 'date');
        $builder->add('end_date', 'date');
    }

    public function getName() {
        return 'customer_campaign';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'DJBlaster\BlasterBundle\Entity\CustomerCampaign',
            'cascade_validation' => true,
        ));
    }

}

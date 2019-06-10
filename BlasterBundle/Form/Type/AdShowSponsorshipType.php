<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use DJBlaster\BlasterBundle\Form\DataTransformer\DateTimeToStringTransformer;

class AdShowSponsorshipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('ad_name', TextType::class, array('label' => 'Title'));
        $builder->add('ad_content', TextareaType::class, array('label' => 'Sponsorship Text'));
        $builder->add(
            $builder->create('begin_time', TextType::class, array('label' => 'Show Begins'))
                ->addModelTransformer(new DateTimeToStringTransformer())
        );

        $builder->add(
            $builder->create('end_time', TextType::class, array('label' => 'Show Ends'))
                ->addModelTransformer(new DateTimeToStringTransformer())
        );

        $builder->add('days_week1', TextType::class, array('label' => 'Week 1'));
        $builder->add('days_week2', TextType::class, array('label' => 'Week 2'));
        $builder->add('days_week3', TextType::class, array('label' => 'Week 3'));
        $builder->add('days_week4', TextType::class, array('label' => 'Week 4'));
    }

    public function getBlockPrefix()
    {
        return 'customer_ad_show_sponsorship';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DJBlaster\BlasterBundle\Entity\AdShowSponsorship',
            'cascade_validation' => true,
        ));
    }
}
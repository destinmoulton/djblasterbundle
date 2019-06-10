<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use DJBlaster\BlasterBundle\Form\DataTransformer\DateTimeToStringTransformer;

class DJSignInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('show_title', TextType::class, array('label' => 'Show Title'));
        $builder->add(
            $builder->create('show_start_time', TextType::class, array('label' => 'Show Time'))
                ->addModelTransformer(new DateTimeToStringTransformer())
        );
        $builder->add($builder->create('show_end_time', TextType::class, array('label' => 'Show End Time'))
            ->addModelTransformer(new DateTimeToStringTransformer()));
        $builder->add('dj_first_name', TextType::class, array('label' => 'DJ First Name'));
        $builder->add('dj_last_name', TextType::class, array('label' => 'DJ Last Name'));
        $builder->add('dj_email', TextType::class, array('label' => 'DJ Email Address'));
        $builder->add('signin', SubmitType::class, array('label' => 'Sign In'));
    }

    public function getBlockPrefix()
    {
        return 'djsignin';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'DJBlaster\BlasterBundle\Entity\DJSignIn', 'cascade_validation' => true,));
    }
}
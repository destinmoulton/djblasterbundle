<?php

namespace DJBlaster\BlasterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DJNotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('message', TextareaType::class, array('label' => 'Message'));

        $builder->add('start_date', DateType::class, array('label' => 'Notification Start Date'));
        $builder->add('end_date', DateType::class, array('label' => 'Notification End Date'));

        $builder->add('primary_color_hex', TextType::class, array('label' => 'Color'));
        $builder->add('button_text', TextType::class, array('label' => 'Button Text'));
    }

    public function getBlockPrefix()
    {
        return 'dj_notification';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DJBlaster\BlasterBundle\Entity\DJNotification',
            'cascade_validation' => true,
        ));
    }
}
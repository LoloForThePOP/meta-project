<?php

namespace App\Form;


use App\Entity\PPBasic;
use App\Form\SlideshowSlideType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SlideshowColReorderType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'slides',
                CollectionType::class,
                [
                    'entry_type' => SlideshowSlideType::class,
                    'label'			=> false,
                    'prototype'			=> true,                    
                    'required'     => false,
                    'by_reference' => true,
                    'delete_empty' => true,
                    'attr' => array(
                        'class' => 'my-selector',
                    ),
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPBasic::class,
        ]);
    }

}

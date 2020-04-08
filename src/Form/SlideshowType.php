<?php

namespace App\Form;


use App\Entity\PPBasic;
use App\Form\SlideshowImagesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SlideshowType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'slides',
                CollectionType::class,
                [
                    'entry_type' => SlideshowImagesType::class,
                    'label'			=> false,
                    'prototype'			=> true,
                    'allow_add'			=> true,
                    'allow_delete'		=> true,
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

<?php

namespace App\Form;

use App\Entity\TextDescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TextDescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add(
                'content', 
                TextareaType::class, 
                [
                    'required'     => false,
                    'sanitize_html' => true,
                    'attr' => [
                        'class' => "tinymce",
                    ],
                ]   
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TextDescription::class,
        ]);
    }
}

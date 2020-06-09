<?php

namespace App\Form;

use App\Entity\Website;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class WebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class, 
                [
                    
                    'label' => 'Adresse du site',
                    'attr' => [
                        
                        'placeholder'    => 'www.example.com',
                    ],
                ]
            )
            ->add('description', TextType::class, 
                [
                    'label' => 'Titre (facultatif)',
                    'attr' => [
                        
                        'placeholder'    => 'Ex : Notre Site Web',
                    ],
                ]
            )
            ->add('position', HiddenType::class, 
                [
                    'attr' => [
                        'readonly' => true,
                        'class'    => 'my-position', // selector is the one used on the js side
                        'autocomplete' => 'off',
                    ],
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}

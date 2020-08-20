<?php

namespace App\Form;

use App\Entity\TechnicalData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TechnicalDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', TextType::class, 
                [
                    'label' => 'Nom de la donnée',

                    'attr' => [
                        
                        'placeholder'    => 'Ex: Budget du Projet',
                    ],

                    'required'   => true,
                ]
            )

            ->add('value', TextareaType::class, 
                [
                    'label' => 'Contenu de la donnée',

                    'attr' => [
                        
                        'placeholder'    => 'Ex : 2300 €',
                    ],

                    'required'   => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TechnicalData::class,
        ]);
    }
}

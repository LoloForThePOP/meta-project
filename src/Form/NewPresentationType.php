<?php

namespace App\Form;

use App\Entity\PPBasic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NewPresentationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add(
            
            'goal', 
            
            TextType::class,

            [
                'label' => 'Objectif du Projet',

                'attr' => [
                    
                    'placeholder'    => 'Écrire ici l\'objectif',
                ],

                'required'   => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPBasic::class,
        ]);
    }
}

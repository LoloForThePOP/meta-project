<?php

namespace App\Form;

use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personnalized', TextType::class, 
                [
                    'label' => 'Personnalisé',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )
            ->add('comments', TextareaType::class, 
                [
                    'label' => 'Ajouter un commentaire ?',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Status::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\ContactWebsite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactWebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Votre adresse email',

                    'attr' => [
                        
                        'placeholder'    => 'Email',
                    ],

                    'required'   => true,
                ]
                )
            ->add(
                'title', 
                TextType::class,
                [
                    'label' => 'Titre du message',

                    'attr' => [
                        
                        'placeholder'    => 'Titre',
                    ],

                    'required'   => true,
                ]
            )
            ->add('content', 
                TextareaType::class, 
                [
                    'label' => 'Contenu du message',

                    'attr' => [
                        
                        'placeholder'    => 'Contenu',
                    ],

                    'required'   => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactWebsite::class,
        ]);
    }
}

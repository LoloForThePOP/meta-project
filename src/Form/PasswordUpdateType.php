<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('oldPassword',
        PasswordType::class,
        $this->getConfiguration("Ancien Mot de Passe", "Votre ancien Mot de Passe?")
        )
        
        ->add('newPassword',
        PasswordType::class,
        $this->getConfiguration("Nouveau Mot de Passe", "Votre nouveau Mot de Passe?")
        )
        
        ->add('confirmPassword',
        PasswordType::class,
        $this->getConfiguration("Confirmation Nouveau Mot de Passe", "Confirmez ici votre nouveau Mot de Passe")
        )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

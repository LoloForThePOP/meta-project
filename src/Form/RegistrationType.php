<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, $this->getConfiguration("Votre adresse e-mail", ""))
            ->add('hash', PasswordType::class, $this->getConfiguration("Créer votre mot de passe", "Mot de passe", ['attr' => ['class' => 'password-field']]))
/*             ->add('hash', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les Deux Mots de Passe ne sont pas les mêmes',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Créer votre Mot de Passe :'],
                    'second_options' => ['label' => 'Répéter votre Mot de Passe :'],
                ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

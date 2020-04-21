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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, $this->getConfiguration("Nom d'Utilisateur :", "Nom d'Utilisateur", ['required'=>false]))
        ->add('email', EmailType::class, $this->getConfiguration("Email :", ""))
        //->add('image',UrlType::class, $this->getConfiguration("Une image pour vous représenter :", "Url de l'Image ici", ['required'=>false]))
        ->add('description', TextareaType::class, $this->getConfiguration("Une Description de Vous ou de votre Organisation :", "Vous pouvez Décrire ici", ['required'=>false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

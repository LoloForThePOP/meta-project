<?php

namespace App\Form;

use App\Entity\Contact;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add(
                'title',
                TextType::class,
                $this->getConfiguration(
                    "Un nom, un titre ?", 
                    "Exemples : Laurent Dupond ; Responsable Communication", 
                    ['required' => false]
                )
            )

            ->add(
                'email1',
                EmailType::class,
                $this->getConfiguration(
                    "Une adresse e-mail ?", 
                    "Écrire ici une adresse e-mail", 
                    ['required' => false]
                )
            )

            ->add(
                'tel1', 
                TelType::class,
                $this->getConfiguration(
                    "Un téléphone ?", 
                    "Écrire ici un numéro de téléphone", 
                    ['required' => false]
                )
            )

            ->add(
                'website1',
                UrlType::class,
                $this->getConfiguration(
                    "Un site web ?", 
                    "Écrire ici une adresse web", 
                    ['required' => false]
                )
            )
 
            ->add(
                'postalMail', 
                TextareaType::class,
                $this->getConfiguration(
                    "Une adresse postale ?", 
                    "Écrire ici une adresse postale", 
                    ['required' => false]
                ))
            ->add(
                'remarks',
                TextareaType::class,
                $this->getConfiguration(
                    "Ajouter des informations, des remarques ?",
                    "Exemples : Heures d'ouverture ; Langues parlées",
                      ['required' => false]
                )
            )
            ->add(
                'email2',
                EmailType::class,
                $this->getConfiguration(
                    "Une autre adresse e-mail ?", 
                    "Vous pouvez l'écrire ici", 
                    ['required' => false]
                )
            )

            ->add(
                'tel2', 
                TelType::class,
                $this->getConfiguration(
                    "Un autre téléphone ?", 
                    "Vous pouvez l'écrire ici", 
                    ['required' => false]
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

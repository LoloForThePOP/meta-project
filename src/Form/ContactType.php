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
                'email1',
                EmailType::class,
                $this->getConfiguration(
                    "Une Adresse E-Mail? (Recommandé)", 
                    "Ecrire ici une Adresse Email", 
                    ['required' => false]
                )
            )
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration(
                    "Un Nom, un Titre?", 
                    "Exemples : Laurent Dupond; ou Responsable Communication; etc", 
                    ['required' => false]
                )
            )
            ->add(
                'tel1', 
                TelType::class,
                $this->getConfiguration(
                    "Un Téléphone?", 
                    "Ecrire le Numéro Ici", 
                    ['required' => false]
                )
            )

            ->add(
                'website1',
                UrlType::class,
                $this->getConfiguration(
                    "Un Site Web?", 
                    "Ecrire son Adresse Ici", 
                    ['required' => false]
                )
            )
 
            ->add(
                'postalMail', 
                TextareaType::class,
                $this->getConfiguration(
                    "Une Adresse Postale?", 
                    "Vous pouvez l'écrire ici", 
                    ['required' => false]
                ))
            ->add(
                'remarks',
                TextareaType::class,
                $this->getConfiguration(
                    "Ajouter des Informations, des Remarques?",
                    "Exemples : Heures d'Ouvertures, Langues Parlées",
                      ['required' => false]
                )
            )
            ->add(
                'email2',
                EmailType::class,
                $this->getConfiguration(
                    "Une Autre Adresse E-Mail?", 
                    "Ecrire Ici", 
                    ['required' => false]
                )
            )

            ->add(
                'tel2', 
                TelType::class,
                $this->getConfiguration(
                    "Un Autre Téléphone?", 
                    "Ecrire le Numéro ici", 
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

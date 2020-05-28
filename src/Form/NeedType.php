<?php

namespace App\Form;

use App\Entity\Need;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NeedType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type', 
                ChoiceType::class, 
                $this->getConfiguration("Type du besoin", "",[
                    'placeholder' => 'Choisir une option',
                    'choices'  => [
                        'Une Compétence (Ex : un Dessinateur, un Développeur)' => 'skill',
                        'Un Service Ponctuel (Ex : Créer deux Dessins, Préparer un Repas)' => 'task',
                        'Un Objet, un Outil, du Matériel (Ex : une Perçeuse)' => 'material',
                        'Un Local, un Terrain, une Surface' => 'area',
                        "Un Conseil" => 'advice',
                        "Une Somme d'Argent" => 'money',
                        'Autre' => 'other',
                        ]
                    ])
                )
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration("Titre du besoin", "Exemple : Un Local à Paris")
                )
            ->add(
                'paidService', 
                ChoiceType::class, 
                $this->getConfiguration("Est-ce payé ?", "",[
                    'placeholder' => 'Choisir une Option',
                    'choices'  => [
                        'Peut-être' => 'maybe',
                        'Oui' => 'yes',
                        'Non' => 'no',
                    ],
                    'required' => false,
                    ]
                )
            )
            ->add(
                'description', 
                TextareaType::class,
                $this->getConfiguration("Description", "Exemple : Nous recherchons un local pour pouvoir développer notre projet. L'idéal serait 30 m² au minimum, si possible à une distance raisonnable du centre ville.", ['required' => false])
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Need::class,
        ]);
    }
}

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
                $this->getConfiguration("Type du Besoin?", "",[
                    'placeholder' => 'Choisir une Option',
                    'choices'  => [
                        'Une Compétence (Ex : un Développeur, un Dessinateur)' => 'skill',
                        'Un Service Ponctuel, Une Tâche (Ex : Créer deux Dessins, Préparer un Repas)' => 'task',
                        'Un Local, un Outil, du Matériel (Ex : une Perçeuse)' => 'material',
                        "Une Somme d'Argent" => 'money',
                        'Autre' => 'other',
                        ]
                    ])
                )
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration("Titre du Besoin :", "")
                )
            ->add(
                'description', 
                TextareaType::class,
                $this->getConfiguration("Description du Besoin :", "", ['required' => false])
                )
            ->add(
                'paidService', 
                ChoiceType::class, 
                $this->getConfiguration("Est-ce Payé ?", "",[
                    'placeholder' => 'Choisir une Option',
                    'choices'  => [
                        'Peut-être' => null,
                        'Oui' => true,
                        'Non' => false,
                    ],
                    'required' => false,
                    ]
                )
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

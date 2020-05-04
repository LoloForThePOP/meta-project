<?php

namespace App\Form;

use App\Entity\PGroup;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PGroupType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name', 
                TextType::class, 
                $this->getConfiguration(
                    "Nom du Nouveau groupe?",
                    "Exemple : Projets Scolaires Médicaments et Ressources Marines"
                    )
                )
            ->add(
                'description', 
                TextareaType::class, 
                $this->getConfiguration(
                    "Une Description pour ce Nouveau Groupe (facultatif) :", 
                    "Exemple : Rassemble les projets liés au cours de Biologie - classe de 3 ème - collège Marie-Curie - 2021.",['required'=>false])
                )
            ->add(
                'keywords', 
                TextareaType::class, 
                $this->getConfiguration(
                    "Des mots-clés facultatifs pour décrire les projets de ce groupe (séparer avec des virgules) :", 
                    "Exemple : océans, algues, médicaments",
                    ['required'=>false])
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PGroup::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\PPBasic;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PPBasicType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('goal', TextareaType::class, $this->getConfiguration("Objectif du Projet", "Objectif du Projet"))

            ->add('title', TextType::class, $this->getConfiguration("Titre du Projet (Optionnel)", "Titre du Projet",['required'=>false]))   

            ->add('keywords', TextType::class, $this->getConfiguration("Mots-Clés (séparer avec des virgules , ) :", "Mots Clés",['required'=>false]))

            ->add('logoFile', VichImageType::class, $this->getConfiguration("Logo du Projet :", "", [
                'required' => false,
                'allow_delete' => true,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => true,
            ]))
            
            ->add('thumbnailFile', VichImageType::class, $this->getConfiguration("Vignette du Projet :", "", [
                'required' => false,
                'allow_delete' => true,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => true,
            ]))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPBasic::class,
        ]);
    }
}

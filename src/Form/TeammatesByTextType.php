<?php

namespace App\Form;

use App\Entity\PPBasic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TeammatesByTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teammatesByText', TextareaType::class, 
            [
                'label' => '',

                'attr' => [
                    'placeholder'    => 'Ici vous pouvez présenter des membres du projet en utilisant une zone de texte. Exemple : Laurent Dupond : jardinier de notre équipe...',

                    'class' => "tinymce",
                ],

                'required'   => false,
                'sanitize_html' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPBasic::class,
        ]);
    }
}

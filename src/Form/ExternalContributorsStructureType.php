<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\ExternalContributorsStructure;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExternalContributorsStructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('title', TextareaType::class, 
                [
                    'label' => 'Résultat',
                    'attr' => [
                        
                        'placeholder'    => '',
                    ],

                    'required'   => true,
                ]
            )

            ->add('richTextContent', TextareaType::class, 
                [
                    'label' => '',

                    'attr' => [
                        'placeholder'    => 'Ici vous pouvez écrire du texte. Exemple : Merci à tous nos contributeurs : Laurent Dupond; Youssef Kouloum; Jeanne D\'ortega...',
                        'class' => "tinymce",
                    ],

                    'required'   => false,
                    'sanitize_html' => true,
                ]
            );

            

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExternalContributorsStructure::class,
        ]);
    }
}

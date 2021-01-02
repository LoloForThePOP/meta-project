<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('title', TextType::class, 
                [
                    'label' => 'Titre (facultatif)',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('textContent', TextareaType::class, 
                [
                    'label' => 'Contenu',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',

                        'class' => "tinymce",
                    ],

                    'sanitize_html' => true,

                    'required'   => false,
                ]
            )

            ->add('image1File', VichImageType::class, 

                array(

                    'label' => 'Image 1',

                    'required' => false,
                    'allow_delete' => true,
                    'download_label' => false,
                    'download_uri' => false,
                    'image_uri' => false,
                    'asset_helper' => true,

                )

            )

            ->add('captionImage1', TextareaType::class, 
                [
                    'label' => 'Légende',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('image2File', VichImageType::class, 

                array(

                    'label' => 'Image 2',

                    'required' => false,
                    'allow_delete' => true,
                    'download_label' => false,
                    'download_uri' => false,
                    'image_uri' => false,
                    'asset_helper' => true,

                )

            )

            ->add('captionImage2', TextareaType::class, 
                [
                    'label' => 'Légende',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('image3File', VichImageType::class, 

                array(

                    'label' => 'Image 3',

                    'required' => false,
                    'allow_delete' => true,
                    'download_label' => false,
                    'download_uri' => false,
                    'image_uri' => false,
                    'asset_helper' => true,

                )

            )

            ->add('captionImage3', TextareaType::class, 
                [
                    'label' => 'Légende',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('video1', TextType::class, 
                [
                    'label' => 'Vidéo 1',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire le code ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('captionVideo1', TextareaType::class, 
                [
                    'label' => 'Légende de la vidéo 1',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('video2', TextType::class, 
                [
                    'label' => 'Vidéo 2',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire le code ici',
                    ],

                    'required'   => false,
                ]
            )

            
            ->add('captionVideo2', TextareaType::class, 
                [
                    'label' => 'Légende de la vidéo 2',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('video3', TextType::class, 
                [
                    'label' => 'Vidéo 3',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire le code ici',
                    ],

                    'required'   => false,
                ]
            )

            
            ->add('captionVideo3', TextareaType::class, 
                [
                    'label' => 'Légende de la vidéo 3',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )




        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}

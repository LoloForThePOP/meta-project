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
                    'label' => 'Titre de la News',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('textContent', TextareaType::class, 
                [
                    'label' => 'Contenu de la News',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => true,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}

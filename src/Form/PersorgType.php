<?php

namespace App\Form;

use App\Entity\Persorg;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PersorgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', TextType::class, 
                [
                    'label' => 'Nom de la personne ou organisation',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => true,
                ]
            )
    

            ->add('imageFile', VichImageType::class, 

                array(

                    'label' => 'Choisir une image, un logo, ou une photo',

                    'required' => false,
                    'allow_delete' => true,
                    'download_label' => false,
                    'download_uri' => false,
                    'image_uri' => false,
                    'asset_helper' => true,

                )

            )

            ->add('missions', TextType::class, 
                [
                    'label' => 'Missions, Rôle dans l\'équipe',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('email', EmailType::class, 
                [
                    'label' => 'Email',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('webdomain1', UrlType::class, 
                [
                    'label' => 'Réseau Social ou Site Web 1',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('webdomain2', UrlType::class, 
                [
                    'label' => 'Réseau Social ou Site Web 2',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('webdomain3', UrlType::class, 
                [
                    'label' => 'Réseau Social ou Site Web 3',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )

            ->add('webdomain4', UrlType::class, 
                [
                    'label' => 'Réseau Social ou Site Web 4',

                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici',
                    ],

                    'required'   => false,
                ]
            )
            
            ->add('description', TextareaType::class, 
                [
                    'label' => 'Ajouter des informations, des remarques ?',

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
            'data_class' => Persorg::class,
        ]);
    }

}

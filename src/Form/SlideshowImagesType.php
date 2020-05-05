<?php

namespace App\Form;

use App\Entity\Slide;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SlideshowImagesType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'slideFile', 
                VichImageType::class, 
                array(
                    'label' 	=> false,
                    'required' 	=> false,
                    'required' => false,
                    'allow_delete' => false,
                    'download_label' => false,
                    'download_uri' => false,
                    'image_uri' => false,
                    'asset_helper' => false,
                )
            )
            ->add(
                'caption', 
                TextareaType::class, 
                $this->getConfiguration(
                    "Légende / Titre (facultatifs) pour cette image :", 
                    "Vous pouvez l'écrire ici",
                    [
                        'required' 	=> false,
                    ]
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slide::class,
        ]);
    }
}

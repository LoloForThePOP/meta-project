<?php

namespace App\Form;

use App\Entity\Slide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddVideoSlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, 
                [
                    'label' => 'Code de la Vidéo : ',
                    'attr' => [
                        
                        'placeholder'    => 'Écrire ici le code de la Vidéo',
                    ],
                ]
            )
            ->add(
                'caption', 
                TextareaType::class, 
                $this->getConfiguration(
                    "Légende / Titre (facultatifs) pour cette vidéo :", 
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

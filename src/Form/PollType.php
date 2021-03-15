<?php

namespace App\Form;

use App\Entity\Website;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('url', UrlType::class, 
                [
                    
                    'label' => 'Adresse web du questionnaire',
                    'attr' => [
                        
                        'placeholder'    => 'www.surveymonkey.com/xFrt23ab',
                    ],
                ]
            )
            
            ->add('description', TextType::class, 
                [
                    'label' => 'Titre du questionnaire (facultatif)',
                    'attr' => [
                        
                        'placeholder'    => 'Exemple : Votre Avis Nous Intéresse',
                    ],
                    'required'   => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}

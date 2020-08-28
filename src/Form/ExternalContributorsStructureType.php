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
            );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExternalContributorsStructure::class,
        ]);
    }
}

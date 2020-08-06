<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //create days list

        $daysList = array_combine(range(1,31),range(1,31));

        //create months + trimester + semester list

        $months = array_combine(range(1,12),range(1,12));

        $trimestersSemesters = 
            
            [

                '1er trimestre' => 13,
                '2ème trimestre' => 36,
                '3ème trimestre' => 69,
                '4ème trimestre' => 912,

                '1er semestre' => 16,
                '2ème semestre' => 612,

            ];

        $monthsList = $months + $trimestersSemesters;

        //create years list

        $yearsList = array_combine(
            
            range( date('Y') - 2, date('Y') + 15) , 
            
            range( date('Y') - 2, date('Y') + 15 ) 
        );


        $builder

            ->add('title', TextType::class, 
                [
                    'label' => 'Titre',
                    'attr' => [
                        
                        'placeholder'    => 'Ex : Début du Projet',
                    ],
                    'required'   => true,
                ]
            )

            ->add('beginYear', ChoiceType::class, 
            
                [
                    'choices' => $yearsList,
                    
                    'placeholder' => 'Année',

                    'required'   => false,
                ]
            )

            ->add('beginMonth', ChoiceType::class, 
            
                [
                    'choices' => $monthsList,
                    
                    'placeholder' => 'Mois / Tr',

                    'required'   => false,
                ]
                
            )

            ->add('beginDay', ChoiceType::class, 
            
                [
                    'choices' => $daysList,
                    
                    'placeholder' => 'Jour',

                    'required'   => false,
                ]

            )

            ->add('endYear', ChoiceType::class, 
            
                [
                    'choices' => $yearsList,
                    
                    'placeholder' => 'Année',

                    'required'   => false,
                ]
            )

            ->add('endMonth', ChoiceType::class, 
            
                [
                    'choices' => $monthsList,
                    
                    'placeholder' => 'Mois / Tr',

                    'required'   => false,
                ]
                
            )

            ->add('endDay', ChoiceType::class, 
            
                [
                    'choices' => $daysList,
                    
                    'placeholder' => 'Jour',

                    'required'   => false,
                ]

            )

            ->add('beginTime', TimeType::class, 
            
            [
                'label' => 'Heure de début (facultatif)',
                
                'placeholder' => [

                    'hour' => 'Heure', 
                    'minute' => 'Minutes', 
                ],

                'required'   => false,
            ])

            ->add('endTime', TimeType::class, 
            
            [
                'label' => 'Heure de fin (facultatif)',
                
                'placeholder' => [

                    'hour' => 'Heure', 
                    'minute' => 'Minutes', 
                ],

                'required'   => false,
            ])

            
            ->add('description', TextareaType::class, 
                [
                    'label' => 'Ajouter des informations, des remarques ?',
                    'attr' => [
                        
                        'placeholder'    => 'Ex : lieu de l\'événement',
                    ],

                    'required'   => false,
                ]
            )


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

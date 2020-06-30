<?php

namespace App\Form;

use App\Entity\QuestionAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuestionAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('question', TextType::class, 
            [
                'label' => 'La question',
                'attr' => [
                    
                    'placeholder'    => 'Écrire ici la question',
                ],
                'required'   => true,
            ]
            )

            ->add('answer', TextareaType::class, 
            [
                'label' => 'La réponse',
                'attr' => [
                    
                    'placeholder'    => 'Écrire ici la réponse',
                ],
                'required'   => true,
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuestionAnswer::class,
        ]);
    }
}

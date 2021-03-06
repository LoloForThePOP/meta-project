<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReplyCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'content', 
                TextareaType::class, 
                [
                    'label' => '',
                    'attr' => [
                        
                        'placeholder'    => 'Votre commentaire',
                    ],
                    'required'   => true,
                ]
            )

            /* We store parent comment id */
                        
            ->add('parentCommentId', HiddenType::class, 
                [

                    'required'   => false,

                    "mapped" => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}

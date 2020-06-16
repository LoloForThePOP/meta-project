<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('senderEmail', EmailType::class, 
                [
                    'label' => 'Votre adresse e-mail',
                    'attr' => [
                        'placeholder' => 'Adresse e-mail',
                        ],
                ]
            )
            ->add('title', TextType::class,
                [
                    'label' => 'Titre du message',
                    'attr' => [
                        'placeholder' => 'Titre du message',
                        ],
                ]
            )
            ->add('content', TextareaType::class,
                [
                    'label' => 'Votre message',
                    'attr' => [
                        'placeholder' => 'Votre message',
                        ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
    }
}

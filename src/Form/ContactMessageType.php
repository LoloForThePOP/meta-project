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
                    'label' => 'Votre Addresse Email :',
                    'attr' => [
                        'placeholder' => 'Addresse Email',
                        ],
                ]
            )
            ->add('title', TextType::class,
                [
                    'label' => 'Titre du Message :',
                    'attr' => [
                        'placeholder' => 'Titre',
                        ],
                ]
            )
            ->add('content', TextareaType::class,
                [
                    'label' => 'Votre Message :',
                    'attr' => [
                        'placeholder' => 'Contenu du Message',
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
